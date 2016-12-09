var MessageList = {
    init: function () {
        var $div = $('#bloc-27 .container');


        // Getting the height of the document
        var n = $div.height();
        console.log(n);

        $div.animate({ scrollTop: n }, 0);
    }
};

$(document).ready(function () {
    MessageList.init();
});