var MessageList = {
    lastReadedConversationId: null,
    init: function () {
        this.cacheElements();
        this.initTop();
        this.bindEvents();
        this.addWidgets();

        this.flagAsReadSelectedConversation();
        this.initSocket();
        
    },
    initSocket: function () {
        MessageList.socket = io.connect(SOCKETURL, {
            query: 'room=' + USERID ,
            autoConnect: true,
            secure: true
        });

        MessageList.socket.on("new_message", function(data) {
            var $selectedConversation = $('div.conversation.selected');

            if (data.conversation_id == $selectedConversation.data('id')) {
                MessageList.appendMessage('incoming-message-template', data.message);
                MessageList.flagAsReadAjax(data.conversation_id);
            } else {
                MessageList.replaceLastMessagePreviewNonSelectedConversation(
                    data.message.substring(0, 100), data.conversation_id);

                MessageList.moveConversationToTop(data.conversation_id);                                        
            }
        });

        MessageList.socket.on("new_conversation", function(data) {
            console.log(data);
            var html    = twig({ref: 'conversation-template'}).render({data: data});            
            var $first  = $('div.conversation:first');

            $first.before(html);
        });
    },
    moveConversationToTop: function (id) {
        var $div = $('div.conversation[data-id="' + id + '"]');

        var $first = $('div.conversation:first');
        $first.before($div);

        $div.addClass('unread');
        $div.find('.message-user-header').addClass('unread');
        $div.find('div.col-sm-1').append();

        $div.find('.unread-dot').removeClass('hidden');
    },
    initTop: function () {
        MessageList.$rightContainer.scrollTop(MessageList.$rightContainer.prop("scrollHeight"));
    },
    flagAsReadSelectedConversation: function () {
        var $selectedConversation = $('div.conversation.selected');
        if ($selectedConversation.length && $selectedConversation.hasClass('unread')) {
            MessageList.lastReadedConversationId = $selectedConversation.data('id');
            setTimeout(MessageList.flagAsReadAjax, 2000);
        }
    },
    cacheElements: function () {
        this.$conversation          = $('div.conversation');
        this.$messagesContainer     = $('div.messages-container');
        this.$selectedConversation  = $('div.conversation.selected');
        this.$send                  = $('a#send');
        this.$messageText           = $('textarea#message-text');
        this.$rightContainer        = $('#bloc-27 .container');
        this.$user                  = $('select#user');
        this.$newConversation       = $('button#new-conversation');
    },
    bindEvents: function () {
        $('body').on('click', 'div.conversation', MessageList.conversationClick);
        this.$newConversation.click(MessageList.newConversationClick);
        $('body').on('click', 'a#send', MessageList.sendClick);
    },
    addWidgets: function () {
       this.$user.select2({
            theme: "bootstrap"
        });     
    },
    newConversationClick: function () {
        var slug = $('select#user').val();
        
        if (slug) {
            window.location = ROOT + 'kapcsolatfelvetel/' + slug;
        }

        return false;
    },
    sendClick: function () {
        var $this = $(this);
        var $messageText = $this.prev('textarea');

        if ($.trim($messageText.val()).length == 0) {
            $messageText.focus();
            return false;
        }

        var $selectedConversation = $('div.conversation.selected');
        MessageList.sendMessageAjax($messageText, $selectedConversation.data('id'));

        return false;
    },
    replaceLastMessagePreview: function (message) {        
        $('div.conversation.selected p.message-preview.hidden-xs').text(message);
        $('div.conversation.selected p.message-preview.visible-xs').text(message.substring(0, 44));
    },
    replaceLastMessagePreviewNonSelectedConversation: function (message, conversationId) {        
        $('div.conversation[data-id="' + conversationId + '"] p.message-preview.hidden-xs').text(message);
        $('div.conversation[data-id="' + conversationId + '"] p.message-preview.visible-xs').text(message.substring(0, 44));
    },
    getLastMessageP: function () {
        return $('.triangle-obtuse').last();
    },
    appendMessage: function (template, message) {
        var html = twig({ref: template}).render({message: message});
        $('textarea#message-text').parents('form#message').before(html);

        MessageList.replaceLastMessagePreview(message.substring(0, 100));
        MessageList.$rightContainer.scrollTop(MessageList.$rightContainer.prop("scrollHeight"));
    },
    sendMessageAjax: function ($messageTextarea, conversationId) {
        var ajax = new AjaxBuilder;
        var success = function (data) {
            MessageList.appendMessage('outgoing-message-template', $messageTextarea.val());

            $messageTextarea.val(null);
            $messageTextarea.focus();
        };

        ajax.data({message: $messageTextarea.val(), conversation_id: conversationId}).url(ROOT + 'message/ajax/send').success(success).send();
    },
    conversationClick: function () {
        var $this = $(this);

        if ($this.hasClass('selected')) {
            return false;
        }

        MessageList.getMessagesAjax($this.data('id'));
        $('div.conversation').removeClass('selected');
        $this.addClass('selected');

        if ($this.hasClass('unread')) {
            MessageList.lastReadedConversationId = $this.data('id');
            MessageList.flagAsReadAjax();
        }
    },
    getMessagesAjax: function (id) {
        var ajax = new AjaxBuilder;
        var success = function (data) {
            MessageList.replaceMessagesInContainer(data.messages);
            window.history.pushState('page2', 'Title', ROOT + 'uzenetek/' + data.conversation.slug);
        };

        ajax.type('GET').data({id: id}).url(ROOT + 'conversation/ajax/getMessages').success(success).send();
    },
    replaceMessagesInContainer: function (data) {
        var html = twig({ref: 'messages-template'}).render({data: data});
        MessageList.$messagesContainer.html(html);
    },
    flagAsReadAjax: function (id) {
        if (typeof id === 'undefined') {
            id = MessageList.lastReadedConversationId;
        }

        var ajax = new AjaxBuilder;
        var success = function (data) {
            MessageList.clearUnread(id);
        };

        ajax.data({id: id}).url(ROOT + 'conversation/ajax/flagAsRead').success(success).send();
    },
    clearUnread: function (id) {
        var $conversationDiv = $('div[data-id="' + id + '"]');
        $conversationDiv.removeClass('unread');
        $conversationDiv.find('.message-user-header').removeClass('unread');

        $conversationDiv.find('.unread-dot').addClass('hidden');
    }
};

$(document).ready(function () {
    MessageList.init();
});