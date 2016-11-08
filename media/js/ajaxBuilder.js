var AjaxBuilder = function () {
    this._url       = "";
    this._type      = "POST";
    this._dataType  = "json";
    this._data      = null;

    this._success = function (data) {
        if (data.error) {
            
        } else {

        }
    };

    this._complete = function (data) {
        if (data.error) {
            
        } else {

        }
    };

    this._error = null;
    this._beforeSend    = null;
    this._error     = null;
};

AjaxBuilder.prototype.url = function (url) {
    this._url = url;
    return this;
};

AjaxBuilder.prototype.type = function (type) {
    this._type = type;
    return this;
};

AjaxBuilder.prototype.dataType = function (dataType) {
    this._dataType = dataType;
    return this;
};

AjaxBuilder.prototype.data = function (data) {
    this._data = data;
    return this;
};

AjaxBuilder.prototype.success = function (success) {
    this._success = success;
    return this;
};

AjaxBuilder.prototype.complete = function (complete) {
    this._complete = complete;
    return this;
};

AjaxBuilder.prototype.error = function (error) {
    this._error = error;
    return this;
};

AjaxBuilder.prototype.beforeSend = function (beforeSend) {
    this._beforeSend = beforeSend;
    return this;
};

AjaxBuilder.prototype.error = function (error) {
    this._error = error;
    return this;
};

AjaxBuilder.prototype.send = function () {
    var self = this;

    var params = {
        url: self._url,
        type: self._type,
        dataType: self._dataType,
        data: self._data
    };

    if (self._success) {
        params.success = self._success;
    }

    if (self._complete) {
        params.complete = self._complete;
    }

    if (self._error) {
        params.error = self._error;
    }

    if (self._beforeSend) {
        params.beforeSend = self._beforeSend;
    }

    if (self._error) {
        params.error = self._error;
    }

    $.ajax(params);
};