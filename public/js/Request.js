class RRequest {
    constructor(url, method) {
        this.url = url;
        this.reqMethod = method;
        this.response = function(data) {};
        this.error = function(error) {
            console.log(error);
        }
        this.progress = function(event) {};
    }
    set onResponse(method) {
        this.response = method;
    }
    set onError(method) {
        this.error = method;
    }
    set onProgress(method){
        this.progress = method;
    }
    send(formdata = null) {
        var clazz = this;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    try {
                        var data = JSON.parse(xhttp.responseText);
                        clazz.response(data);
                    } catch (e) {
                        clazz.response(xhttp.responseText);
                    }
                    return;
                }
                var error = JSON.parse(xhttp.responseText);
                clazz.error(error, this.status);
                return;
            }
        };
        xhttp.onprogress = this.progress;
        xhttp.open(this.reqMethod, this.url);
        xhttp.send(formdata);
    }
}
