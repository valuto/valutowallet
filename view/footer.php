<?php //PLEASE DO NOT REMOVE OR CHANGE THE POWERED BY LINK. THIS IS THE ONLY RECOGNITION I ASK FOR ?>
            </div>
        </main>

        <footer>

            <div class="container-fluid">

                <div class="row">
                    
                    <div class="col-md-4">
                        <img src="/assets/img/logo-small.png" width="130" />
                        <p>
                            <?php echo lang('MASTER_FOOTER_DESCR'); ?>
                        </p>
                        <p class="copyright">&copy; <?=date('Y')?> <?=config('app', 'fullname')?> - <?php echo lang('MASTER_FOOTER_RIGHTSRESERVED'); ?></p>
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        
                    </div>

                </div>

            </div>

        </footer>

        <script src="/assets/js/vue.js"></script>
        <?php if (!empty($_SESSION['user_session'])): ?>
        <script src="/assets/js/vueapp.js"></script>
        <?php endif; ?>
    </body>
</html>
