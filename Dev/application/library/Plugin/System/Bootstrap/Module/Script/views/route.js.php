// initialize the application
$(document).ready(function() {

    var app = Sammy('#content', function() {
        <?php
        foreach($navigation as $data) { if(isset($menu['ajax_load']) && $menu['ajax_load']==false) { } else { ?>
        this.get('#<?=$data['path']?>', function() {
            var $newActiveLink = $( 'a[href="#'+ this.path.replace('/#/', '/') +'"]' );
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

            <?php if(isset($data['show_loading'])) { foreach($data['show_loading'] as $loadElem) { ?>

            $('<?=$loadElem?>').block({
                message: '<img src="/assets/img/ajax-loader-1.svg"/>'
            });

            <?php }} ?>


            this.load(this.path.replace('/#/', '/'), {
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

        <?php if(isset($data['additional_routes'])) { foreach($data['additional_routes'] as $route) { ?>

        this.get('#<?=$route?>', function() {
            var $newActiveLink = $( 'a[href="#'+ this.path.replace('/#/', '/') +'"]' );
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
            this.load(this.path.replace('/#/', '/'), {
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
    app.run('#/app');

});
