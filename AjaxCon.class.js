function AjaxCon() {

    this.callback = false;

    this.ajaxFailure = function (returnData) {};

    this.ajaxSuccess = function (returnData) {};

    this.call = function(functionName, argumentArray, callback){
        $.ajax( $.extend(true, {}, this.ajaxSettings, {data:{'ajaxFunction':functionName, 'arguments':argumentArray}} ) );
        this.callback = callback;
    };

    this.ajaxReturn = function (returnData){
        returnData = returnData.substring(returnData.indexOf("{") - 1);
        returnData = returnData.substring(0, returnData.lastIndexOf("}") + 1);
        var responseArray = $.parseJSON(returnData);

        if (responseArray.Message.toLowerCase() != "success"){
            this.ajaxFailure(responseArray);
            return;
        }

        this.ajaxSuccess(responseArray);
        if(this.callback != false) this.callback(responseArray);
    };

    window.ajaxCon = this;
    this.ajaxSettings = { url: window.location.href, type: 'POST', cache: false, data: {ajaxRequest: true}, success: function (returnData) {
        ajaxCon.ajaxReturn(returnData)
    }};
}