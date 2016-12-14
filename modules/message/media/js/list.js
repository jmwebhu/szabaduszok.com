var MessageList = {
    init: function () {
        this.initTop();
        this.cacheElements();
        this.bindEvents();
    },
    initTop: function () {
        var $div = $('#bloc-27 .container');
        var n = $div.height();

        $div.animate({ scrollTop: n }, 0);
    },
    cacheElements: function () {
        this.$conversation      = $('div.conversation');
        this.$messagesContainer = $('div.messages-container');
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
            MessageList.flagAsReadAjax($this.data('id'));
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
    flagAsReadAjax: function (id) {
        var ajax = new AjaxBuilder;
        var success = function (data) {
            MessageList.clearUnread(id);
        };

        ajax.data({id: id}).url(ROOT + 'conversation/ajax/flagAsRead').success(success).send();
    },
    clearUnread: function (id) {
        var $conversationDiv = $('div[data-id="' + id + '"]');
        $conversationDiv.removeClass('unread');
        $conversationDiv.find('.unread-dot').hide();
        $conversationDiv.find('.message-user-header').removeClass('unread');
    },
};

$(document).ready(function () {
    MessageList.init();
});