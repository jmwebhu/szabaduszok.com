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
        this.$conversation = $('div.conversation');
    },
    bindEvents: function () {
        this.$conversation.click(MessageList.conversationClick);
    },
    conversationClick: function () {
        var $this = $(this);
        
        MessageList.getMessagesAjax($this.data('id'));
    },
    getMessagesAjax: function (id) {
        var ajax = new AjaxBuilder;
        var success = function (data) {
            console.log(data);
        };

        var error = function () {
            console.log('error');
        };

        ajax.data({id: id}).url(ROOT + 'conversation/ajax/getMessages').success(success).error(error).send();
    }
};

$(document).ready(function () {
    MessageList.init();
});