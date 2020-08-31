<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-white display-i text-capitalize"><?php echo lang("language_settings") ?></h1>
                    <button type="button" class="btn btn-light btn-sm display-i ft-right text-capitalize" data-toggle="modal" data-target="#form-modaladdLang"><?php echo lang("add_language") ?></button>
                    <div class="dt-entry__header mt-1m">
                        <!-- Entry Heading -->
                        <div class="dt-entry__heading">
                        </div>
                        <!-- /entry heading -->
                    </div>
                </div>
                <!-- /page header -->
            </div>
            <!-- /profile banner -->

            <!-- Modal -->
            <div class="modal fade display-n" id="form-modaladdLang" tabindex="-1" role="dialog"
                                    aria-labelledby="model-8" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">

                    <!-- Modal Content -->
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h3 class="modal-title text-capitalize" id="model-8"><?php echo lang("add_language") ?></h3>
                            <button type="button" class="close"
                                data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <!-- /modal header -->
                        <?php echo form_open( base_url( 'settings/addLanguage' ) , array( 'class' => 'lang','id' => 'addLang', 'enctype=' => 'multipart/form-data' ));?>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="form-group mb--5">
                                <label class="text-capitalize" for="lname"><?php echo lang("language_name") ?></label>
                                <input type="text" name="lname" class="form-control " id="newlname" aria-describedby="lname" placeholder="Language">
                                <label class="error" for="lname"></label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize" for="lcode"><?php echo lang("language_code") ?></label>
                                <input type="text" name="lcode" class="form-control " id="newlcode" aria-describedby="lcode" placeholder="Code">
                                <label class="error" for="lcode"></label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize" for="logo"><?php echo lang("language_logo") ?></label>
                                <input type="file" aria-describedby="logo" name="logo" id="newlang_logo_upload">
                                <input type="text" name="logoUpload" hidden>
                            </div>
                        </div>
                        <!-- /modal body -->

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button"
                                class="btn btn-secondary btn-sm text-capitalize"
                                data-dismiss="modal"><?php echo lang("cancel") ?>
                            </button>
                            <button type="submit"
                                class="btn btn-primary btn-sm text-capitalize"><?php echo lang("add_language") ?>
                            </button>
                        </div>
                        <!-- /modal footer -->
                        <?php echo form_close();?>

                    </div>
                    <!-- /modal content -->

                </div>
            </div>
            <!-- /modal -->

            <!-- Profile Content -->
            <div class="profile-content">

                <!-- Grid -->
                <div class="row">

                    <!-- Grid Item -->
                    <div class="col-xl-12">

                        <!-- Module -->
                        <div class="dt-module">

                            <!-- Module Sidebar -->
                            <div class="dt-module__sidebar">

                                <!-- Sidebar Header -->
                                <div class="dt-module__sidebar-header border-bottom">
                                    <div class="d-none d-md-flex align-items-center">
                                        <span class="h3 mb-0 text-capitalize"><?php echo lang("languages_list") ?></span>
                                    </div>

                                </div>
                                <!-- /sidebar header -->

                                <!-- Sidebar Menu -->
                                <div class="dt-module__sidebar-content ps-custom-scrollbar ps">
                                    <!-- Contacts -->
                                    <div class="dt-contacts contacts-list">

                                        <?php
                                            if(!empty($languages))
                                            {
                                                foreach($languages as $language)
                                                {
                                            ?>
                                        <!-- Contact -->
                                        <button type="submit" class="langSelect dt-contact bg-white w-100 text-left border-n <?php echo $langID == $language->id ? 'dt-contact-active' : '' ; ?>" id="template<?php echo $language->id ?>" data-id="<?php echo $language->id ?>">
                                            <!-- Contact Info -->
                                            <div class="dt-contact__info">
                                                <h4 class="dt-module-side-nav__text mt-1m text-capitalize col-md-9 pl-0"><?php echo $language->name ?></h4>
                                                <span class="col-md-3"><i class="icon icon-trash icon-fw icon-lg"></i></span>
                                            </div>
                                            <!-- /contact info -->
                                        </button>
                                        <!-- /contact -->
                                        <?php
                                                }
                                            }
                                            ?>
                                    </div>
                                    <!-- contacts -->
                                </div>
                                <!-- /sidebar Menu -->

                            </div>
                            <!-- /module sidebar -->

                            <!-- Module Container -->
                            <div class="dt-module__container">
                                <div class="loader mt-20 hide" id="loader"></div>
                                    <!-- Module Header -->
                                <div class="dt-module__header z0 d-none d-md-flex">
                                    <div class="row w-100">
                                        <div class="col-md-8">
                                            <div class="mt-2">
                                                <img class="flag-icon flag-icon-rounded flag-icon-lg mr-1m" id="langLogo" src="<?php echo base_url('uploads/').$langLogo ?>">
                                                <span class="h3 mb-0 mr-1m font-1-2m text-capitalize"><d id="langName"><?php echo $langName ?></d> - <d id="langCode" class="text-uppercase"><?php echo $langCode ?></d></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <button type="button" class="btn btn-info method-button text-capitalize" style="border-radius: 3px;float: right;" data-toggle="modal" data-target="#form-modaleditLang"><?php echo lang("edit_language") ?></button>
                                        </div>
                                    </div>
                                <!-- /search box -->
                                </div>
                                <!-- /module header -->
                                
                                <!-- Modal -->
                                <div class="modal fade display-n" id="form-modaleditLang" tabindex="-1" role="dialog"
                                    aria-labelledby="model-8" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">

                                        <!-- Modal Content -->
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h3 class="modal-title text-capitalize" id="model-8"><?php echo lang("edit_language") ?></h3>
                                                <button type="button" class="close"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <!-- /modal header -->
                                            <?php echo form_open( base_url( 'settings/editLanguage' ) , array( 'class' => 'lang','id' => 'editLang', 'enctype=' => 'multipart/form-data' ));?>
                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <input type="text" name="lid" class="form-control " id="lid" value="<?php echo $langID ?>" hidden>
                                                <div class="form-group mb--5">
                                                    <label class="text-capitalize" for="lname"><?php echo lang("language_name") ?></label>
                                                    <input type="text" name="lname" class="form-control " id="lname" value="<?php echo $langName ?>" aria-describedby="lname" placeholder="Language">
                                                    <label class="error" for="lname"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-capitalize" for="lcode"><?php echo lang("language_code") ?></label>
                                                    <input type="text" name="lcode" class="form-control " id="lcode" value="<?php echo $langCode ?>" aria-describedby="lcode" placeholder="Code">
                                                    <label class="error" for="lcode"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="text-capitalize" for="logo"><?php echo lang("language_logo") ?></label>
                                                    <input type="file" aria-describedby="logo" name="logo" id="lang_logo_upload">
                                                </div>
                                            </div>
                                            <!-- /modal body -->

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button"
                                                    class="btn btn-secondary btn-sm text-capitalize"
                                                    data-dismiss="modal"><?php echo lang("cancel") ?>
                                                </button>
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm text-capitalize"><?php echo lang("save") ?>
                                                </button>
                                            </div>
                                            <!-- /modal footer -->
                                            <?php echo form_close();?>

                                        </div>
                                        <!-- /modal content -->

                                    </div>
                                </div>
                                <!-- /modal -->
                                <!-- Module Content -->
                                <div class="dt-module__content ps-custom-scrollbar ps">

                                    <!-- Module Content Inner -->
                                    <div class="">
                                        <!-- Card Body -->
                                        <div class="dt-card__body">
                                        <div class="hide" id="LangModulesLoader" style="height:10em">
                                        <div class="loader"></div>
                                        </div>
                                            <div class="row" id="AllLangModules">
                                                <?php
                                                if(!empty($langModules))
                                                {
                                                    foreach($langModules as $langModule)
                                                    {
                                                ?>
                                                <div class="col-sm-4">
                                                    <div class="dt-card dt-card__full-height text-dark">
                                                        <!-- Card Body -->
                                                        <div class="dt-card__body p-xl-4 py-sm-8 py-6 px-4">
                                                            <!-- Media -->
                                                            <div class="media">
                                                                <!-- Media Body -->
                                                                <div class="media-body row">
                                                                    <div class="col-md-6 display-6 mb-1 init-counter text-capitalize"><?php echo $langModule->lang_name; ?></div>
                                                                    <?php echo form_open( base_url( 'settings/getLangSettings' ) , array( 'class' => 'col-md-6 settingsLang', 'data-id'=>$langID, 'id'=>$langModule->id ));?>
                                                                    <button type="submit" class="btn btn-info method-button" style="border-radius: 3px;">Change</button>  
                                                                    <?php echo form_close();?>                                          
                                                                </div>
                                                                <!-- /media body -->
                                                            </div>
                                                            <!-- /media -->
                                                        </div>
                                                        <!-- /card body -->
                                                    </div>
                                                </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- /card body -->
                                    </div>
                                    <!-- /module content inner -->
                                </div>
                                <!-- /module content -->
                            </div>
                            <!-- /module container -->
                        </div>
                        <!-- /module -->

                    </div>
                    <!-- /grid item -->

                </div>
                <!-- /grid -->

            </div>
            <!-- /profile content -->

        </div>
        <!-- /Profile -->
        <aside class="dt-customizer dt-drawer w-65 position-right">
            <div class="dt-customizer__inner">
                <!-- Customizer Header -->
                <div class="dt-customizer__header">
                    <!-- Customizer Title -->
                    <div class="dt-customizer__title">
                        <h3 class="mb-0 text-capitalize" id="lang-header">English Settings: Common Language</h3>
                    </div>
                    <!-- /customizer title -->
                    <!-- Close Button -->
                    <button type="button" class="close" data-toggle="customizer">
                        <span aria-hidden="true">×</span>
                    </button>
                    <!-- /close button -->
                </div>
                <!-- /customizer header -->

                <!-- Customizer Body -->
                <div class="dt-customizer__body ps-custom-scrollbar ps ps--active-y">
                <div class="loader" id="LangSettingsloader"></div>
                    <!-- Customizer Body Inner  -->
                    <div class="dt-customizer__body-inner" id="sideContent">
                        <?php echo form_open(base_url( 'settings/editTranslation' ) , array( 'id' => 'langForm', 'class' => 'mt--30p form-group row' ));?>
                        <input id="transLangId" name="langId" value="<?php echo $langID ?>" hidden>
                        <table class="table table-striped mb-0 hide" id="table">
                            <thead class="thead-light">
                                <tr role="row">
                                    <th>Title</th>
                                    <th>Translation</th>
                                </tr>
                            </thead>
                            <tbody id="settingsList">
                            </tbody>
                        </table>
                        <button class="btn btn-info w-100 hide text-capitalize" name="save" id="settingsSave"><?php echo lang("save") ?></button>
                        <?php echo form_close();?>
                    </div>
                    <!-- /customizer body inner -->
                </div>
                <!-- /customizer body -->

            </div>
        </aside>
        

    </div>
<!-- /site content -->
<script src="<?php echo base_url('/assets/dist/js/languages.js') ?>"></script>