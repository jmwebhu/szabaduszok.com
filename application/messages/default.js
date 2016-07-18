function notifyUser(message) {
    /**
     * @todo VALAMILYEN NOTIFY PLUGIN TELEPITESE
     */
    console.log(message);
};

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
