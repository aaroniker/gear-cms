<?php
    admin::addButton('
        <a @click="addDirModal = true" class="button border">
            '.lang::get('add_dir').'
        </a>
    ');
    admin::addButton('
        <form class="upload" method="post" action="'.url::admin('content', ['media', 'upload']).'" enctype="multipart/form-data">
            <div class="button">
                <a>
                    <span>'.lang::get('upload').'</span>
                    <svg class="load" version="1.1"x="0px" y="0px" viewBox="0 0 40 40" enable-background="new 0 0 40 40">
                        <path opacity="0.3" fill="#fff" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
                        s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
                        c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
                        <path fill="#fff" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
                        C22.32,8.481,24.301,9.057,26.013,10.047z">
                        <animateTransform attributeType="xml"
                        attributeName="transform"
                        type="rotate"
                        from="0 20 20"
                        to="360 20 20"
                        dur="0.5s"
                        repeatCount="indefinite"/>
                        </path>
                    </svg>
                    <svg class="check" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </a>
                <div>
                    <span></span>
                </div>
            </div>
            <input class="file" type="file" name="file">
            <input type="hidden" name="path" :value="path">
        </form>
    ');
    admin::$search = true;

    $form = new form();
    $form->setHorizontal(false);

    $form->addFormAttribute('v-on:submit.prevent', 'addDir');

    $field = $form->addTextField('name', '');
    $field->fieldName(lang::get('name'));
    $field->addAttribute('v-model', 'dirName');
    $field->fieldValidate();

    $field = $form->addRawField('<p class="static">{{ path }}<span class="text-light" v-if="dirName" v-text="dirName"></span></p>');
    $field->fieldName(lang::get('path'));

?>

<modal v-if="addDirModal" @close="addDirModal = false">
    <h3 slot="header"><?=lang::get('add_dir'); ?></h3>
    <div slot="content">
        <?=$form->show(); ?>
    </div>
</modal>

<file-table :headline="headline" :search="search"></file-table>

<?php
theme::addJSCode('
    window.addEventListener("touchmove", function() {});
    new Vue({
        el: "#app",
        data: {
            headline: lang["media"],
            path: "/",
            search: "",
            showSearch: true,
            addDirModal: false,
            dirName: ""
        },
        events: {
            path: function(data) {
                this.path = data;
                $("#upload ul").html("");
            }
        },
        created: function() {

            var vue = this;

            eventHub.$on("setPath", function(path) {
                vue.path = path;
            });

            eventHub.$on("setHeadline", function(data) {
                vue.headline = data.headline;
                vue.showSearch = data.showSearch;
            });

            $(document).ready(function() {

                $(".upload").on("click", ".button a span", function(e) {
                    e.preventDefault();
                    var btn = $(this).parent().parent();
                    btn.next(".file").trigger("click");
                });

                $(".upload .file").on("change", function() {

                    var upload = $(this).parent();
                    var btn = upload.children(".button");
                    var loadSVG = btn.children("a").children(".load");
                    var loadBar = btn.children("div").children("span");
                    var checkSVG = btn.children("a").children(".check");

                    btn.children("a").children("span").fadeOut(200, function() {
                        btn.children("a").animate({
                            width: 36
                        }, 100, function() {
                            loadSVG.fadeIn(300);
                            btn.animate({
                                width: 180
                            }, 200, function() {
                                btn.children("div").fadeIn(200, function() {
                                    upload.ajaxSubmit({
                                    	uploadProgress: function(event, position, total, percentComplete) {
                                    		loadBar.width(percentComplete + "%");
                                    	},
                                    	complete: function(data) {
                                            loadSVG.fadeOut(100, function() {
                                                checkSVG.fadeIn(100, function() {
                                                    setTimeout(function() {
                                                        jQuery.event.trigger("fetch");
                                                        btn.children("div").fadeOut(100, function() {
                                                            loadBar.width(0);
                                                            checkSVG.fadeOut(100, function() {
                                                                btn.children("a").animate({
                                                                    width: 105
                        	                                    }, 200);
                        	                                    btn.animate({
                        		                                    width: 105
                        	                                    }, 200, function() {
                        		                                    btn.children("a").children("span").fadeIn(100);
                        	                                    });
                                                            });
                                                        });
                                                    }, 1000);
                                                });
                                            });
                                    	}
                                    });
                                });
                            });
                        });
                    });
                });

            });

        },
        methods: {
            addDir: function() {

                var vue = this;

                $.ajax({
                    method: "POST",
                    url: "'.url::admin('content', ['media', 'addDir']).'",
                    dataType: "text",
                    data: {
                        name: vue.dirName,
                        path: vue.path
                    },
                    success: function(data) {
                        jQuery.event.trigger("fetch");
                        vue.addDirModal = false;
                        vue.dirName = "";
                    }
                });

            }
        }
    });
');
?>
