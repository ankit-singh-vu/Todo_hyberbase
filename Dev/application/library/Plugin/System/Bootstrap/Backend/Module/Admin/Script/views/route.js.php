// initialize the application
$(document).ready(function() {

    var app = Sammy('#content', function() {
        <?php //___debug($navigation);
        foreach($navigation as $data) { if(isset($menu['ajax_load']) && $menu['ajax_load']==false) { } else { ?>
        this.get('#<?=str_replace('/admin_', '/',$data['path'])?>', function() {
            var $newActiveLink = $( 'a[href="#<?=str_replace('/admin_','/',$data['path'])?>"]' );
            $newActiveLink.blur();
            $('body').find('li.active').removeClass('active');
            //$('#right-menu').find('.active').removeClass('active');
            $newActiveLink.closest('li').addClass('active').parents('li').addClass('active');
            this.load('<?=$data['path']?>', {
                cache: false,
                error: function(response) {
                    //console.log(response);
                    if(typeof response.responseText !== 'undefined') {
                        $('#content').html(response.responseText);
                    }
                }
            }).swap();
        });

        <?php if(isset($data['additional_routes'])) { foreach($data['additional_routes'] as $route) { ?>

        this.get('#<?=str_replace('/admin_', '/',$route)?>', function() {
            var $newActiveLink = $( 'a[href="#'+ this.path.replace('/#/', '/').replace('/admin/', '/admin_') +'"]' );
            $newActiveLink.blur();
            $('body').find('li.active').removeClass('active');
            $newActiveLink.closest('li').addClass('active');
            <?php if(isset($data['activate_links'])) { foreach($data['activate_links'] as $active_link) { ?>
            var active_link = '<?=$active_link?>';
            for(var pkey in this.params) {
                active_link = active_link.replace(':' + pkey, this.params[pkey]);
            }
            $( 'a[href="#'+ active_link +'"]' ).closest('li').addClass('active');
            <?php }} ?>
            this.load(this.path.replace('/#/', '/').replace('/admin/', '/admin_'), {
                cache: false,
                error: function(response) {
                    //console.log(response);
                    if(typeof response.responseText !== 'undefined') {
                        $('#content').html(response.responseText);
                    }
                }
            }, function() {
                setup_page();
            }).swap();
            $('#content').attr('source', this.path.replace('/#/', '/'));
        });

        <?php }}  ?>


        <?php }} ?>
    });

    // start the application
    app.run('#/dashboard');

});
