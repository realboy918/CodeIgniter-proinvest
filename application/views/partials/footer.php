<?php if($pageTitle=="Login" OR $this->uri->segment(1)=="signup" OR $pageTitle=="Forgot Password" OR $pageTitle=="Reset Password") {?>
<?php } else { ?>
<!-- Footer -->
<footer class="dt-footer">

    Copyright <?php echo $this->security->xss_clean($this->companyName); ?> © <?php echo date ('Y'); ?>
</footer>
<!-- /footer -->

</div>
<!-- /site content wrapper -->
</main>
</div>
</div>
<?php } ?>
<?php if($this->chatPluginActive == 1) {?>
    <?php if($this->chatPlugin == 'Tawk') {?>
        <p class="hidden" id="tawk" data-value="<?php echo 'https://embed.tawk.to/'.$this->tawkpropertyid.'/default' ?>">
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var ppid = $('#tawk').attr('data-value');   
            var Tawkurl = ppid;
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src=Tawkurl;
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
            })();
        </script>
        <!--End of Tawk.to Script-->
    <?php }?>
<?php }?>
<script src="<?php echo base_url(); ?>assets/dist/summernote/summernote-bs4.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/lang.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/summernote/editor-summernote.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/moment/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/contact.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/perfect-scrollbar.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/masonry.pkgd.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/sweetalert2.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/customizer.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/chartist.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/script.js"></script>
</body>
</html>