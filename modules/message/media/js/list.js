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
    }
};

$(document).ready(function () {
    MessageList.init();
});