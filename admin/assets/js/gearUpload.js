/*
 *    gearUpload.js - 0.2
 *    http://gearcms.org
*/

(function($) {

    var pluginName = 'gearUpload';

    var defaults = {
        url: document.URL,
        method: 'POST',
        data: {},
        maxFileSize: 0,
        maxFiles: 0,
        allowedTypes: '*',
        extFilter: null,
        dataType: null,
        fileName: 'file',
        uploadDelay: 0,
        eventInit: function() {},
        eventFallbackMode: function(message) {},
        eventNewFile: function(id, file) {},
        eventBeforeUpload: function(id) {},
        eventComplete: function() {},
        eventUploadProgress: function(id, percent) {},
        eventUploadSuccess: function(id, data) {},
        eventUploadError: function(id, message) {},
        eventFileTypeError: function(file) {},
        eventFileSizeError: function(file) {},
        eventFileExtError: function(file) {},
        eventFilesMaxError: function(file) {}
    };

    var methods = {
        reload: function() {
            alert();
        }
    };

    var gearUpload = function(element, options) {

        this.element = $(element);
        this.settings = $.extend({}, defaults, options);

        if(!this.checkBrowser()) {
            return false;
        }

        this.init();

        return true;

    };

    gearUpload.prototype.checkBrowser = function() {

        if(window.FormData === undefined) {
            return false;
        }

        if(this.element.find("input[type=file]").length > 0) {
            return true;
        }

        if(!this.checkEvent("drop", this.element) || !this.checkEvent("dragstart", this.element)) {
            return false;
        }

        return true;

    };

    gearUpload.prototype.checkEvent = function(eventName, element) {

        var element = element || document.createElement("div");
        var eventName = "event" + eventName;

        var isSupported = eventName in element;

        if(!isSupported) {

            if(!element.setAttribute) {
                element = document.createElement('div');
            }

            if(element.setAttribute && element.removeAttribute) {

                element.setAttribute(eventName, "");

                isSupported = typeof element[eventName] == "function";

                if(typeof element[eventName] != "undefined") {
                    element[eventName] = undefined;
                }

                element.removeAttribute(eventName);

            }

        }

        element = null;

        return isSupported;

    };

    gearUpload.prototype.init = function() {

        var _this = this;

        _this.queue = new Array();
        _this.queuePos = -1;
        _this.queueRunning = false;

        _this.element.on("drop", function(e) {

            e.preventDefault();

            var files = e.originalEvent.dataTransfer.files;

            _this.queueFiles(files);

        });

        _this.element.find("input[type=file]").on("change", function(e) {

            var files = e.target.files;

            _this.queueFiles(files);

            $(this).val("");

        });

        this.settings.eventInit.call(this.element);

    };

    gearUpload.prototype.queueFiles = function(files) {

        var lenght = this.queue.length;

        for(var i= 0; i < files.length; i++) {

            var file = files[i];

            if((this.settings.maxFileSize > 0) && (file.size > this.settings.maxFileSize)) {
                this.settings.eventFileSizeError.call(this.element, file);
                continue;
            }

            if((this.settings.allowedTypes != "*") && !file.type.match(this.settings.allowedTypes)) {
                this.settings.eventFileTypeError.call(this.element, file);
                continue;
            }

            if(this.settings.extFilter != null) {

                var extList = this.settings.extFilter.toLowerCase().split(";");

                var ext = file.name.toLowerCase().split(".").pop();

                if($.inArray(ext, extList) < 0) {
                    this.settings.eventFileExtError.call(this.element, file);
                    continue;
                }

            }

            if(this.settings.maxFiles > 0) {
                if(this.queue.length >= this.settings.maxFiles) {
                    this.settings.eventFilesMaxError.call(this.element, file);
                    continue;
                }
            }

            this.queue.push(file);

            var index = this.queue.length - 1;

            this.settings.eventNewFile.call(this.element, index, file);

        }

        if(this.queueRunning) {
            return false;
        }

        if(this.queue.length == lenght) {
            return false;
        }

        this.processQueue();

        return true;

    };

    gearUpload.prototype.processQueue = function() {

    var _this = this;

        setTimeout(function() {

            _this.queuePos++;

            if(_this.queuePos >= _this.queue.length) {

                _this.settings.eventComplete.call(_this.element);
                _this.queuePos = (_this.queue.length - 1);
                _this.queueRunning = false;

                return;

            }

            var file = _this.queue[_this.queuePos];

            var formData = new FormData();

            formData.append(_this.settings.fileName, file);

            var checkContinue = _this.settings.eventBeforeUpload.call(_this.element, _this.queuePos);

            if(false === checkContinue) {
                return;
            }

            $.each(_this.settings.data, function(exKey, exVal) {
                formData.append(exKey, exVal);
            });

            _this.queueRunning = true;

            $.ajax({
                url: _this.settings.url,
                type: _this.settings.method,
                dataType: _this.settings.dataType,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                forceSync: false,
                xhr: function() {
                    var xhrobj = $.ajaxSettings.xhr();
                    if(xhrobj.upload) {
                        xhrobj.upload.addEventListener('progress', function(e) {
                            var percent = 0;
                            var position = e.loaded || e.position;
                            var total = e.total || e.totalSize;
                            if(e.lengthComputable){
                                percent = Math.ceil(position / total * 100);
                            }
                            _this.settings.eventUploadProgress.call(_this.element, _this.queuePos, percent);
                        }, false);
                    }
                    return xhrobj;
                },
                success: function (data, message, xhr) {
                    _this.settings.eventUploadSuccess.call(_this.element, _this.queuePos, data);
                },
                error: function (xhr, status, message) {
                    _this.settings.eventUploadError.call(_this.element, _this.queuePos, message);
                },
                complete: function(xhr, textStatus) {
                    _this.processQueue();
                }
            });

        }, _this.settings.uploadDelay);

    }

    $.fn.gearUpload = function(options) {

        if(methods[options]) {
            return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if(typeof options === 'object' || !options) {
            return this.each(function() {
                if(!$.data(this, pluginName)) {
                    $.data(this, pluginName, new gearUpload(this, options));
                }
            });
        }

    };

    $(document).on("dragenter dragover drop", function(e) {
        e.stopPropagation();
        e.preventDefault();
    });

})(jQuery);
