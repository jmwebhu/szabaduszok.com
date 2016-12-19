var MessageList = {
    lastReadedConversationId: null,
    init: function () {
        this.initTop();
        this.cacheElements();
        this.bindEvents();

        this.flagAsReadSelectedConversation();
    },
    initTop: function () {
        var $div = $('#bloc-27 .container');
        var n = $div.height();

        $div.animate({ scrollTop: n }, 0);
    },
    flagAsReadSelectedConversation: function () {
        if (MessageList.$selectedConversation.length && MessageList.$selectedConversation.hasClass('unread')) {
            MessageList.lastReadedConversationId = MessageList.$selectedConversation.data('id');
            setTimeout(MessageList.flagAsReadAjax, 2000);
        }
    },
    cacheElements: function () {
        this.$conversation          = $('div.conversation');
        this.$messagesContainer     = $('div.messages-container');
        this.$selectedConversation  = $('div.conversation.selected');
    },
    bindEvents: function () {
        this.$conversation.click(MessageList.conversationClick);
    },
    conversationClick: function () {
        var $this = $(this);

        if ($this.hasClass('selected')) {
            return false;
        }

        MessageList.getMessagesAjax($this.data('id'));
        MessageList.$conversation.removeClass('selected');
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

        ajax.data({id: id}).url(ROOT + 'conversation/ajax/getMessages').success(success).send();
    },
    replaceMessagesInContainer: function (data) {
        var html = twig({ref: 'messages-template'}).render({data: data});
        MessageList.$messagesContainer.html(html);
    },
    flagAsReadAjax: function () {
        var ajax = new AjaxBuilder;
        var success = function (data) {
            MessageList.clearUnread(MessageList.lastReadedConversationId);
        };

        ajax.data({id: MessageList.lastReadedConversationId}).url(ROOT + 'conversation/ajax/flagAsRead').success(success).send();
    },
    clearUnread: function (id) {
        var $conversationDiv = $('div[data-id="' + id + '"]');
        $conversationDiv.removeClass('unread');
        $conversationDiv.find('.message-user-header').removeClass('unread');

        $conversationDiv.find('.unread-dot').hide('slow');
    },
};

$(document).ready(function () {
    MessageList.init();
});