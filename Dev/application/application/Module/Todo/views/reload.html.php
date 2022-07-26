
<table class="table table-striped ">
    <tbody>
    <?php 
        // echo '<pre>';print_r($user);
        foreach ($todos as  $key => $value){ 
    ?>
    <tr>
        <td><input type="checkbox"  <?php if($value->is_striked)echo 'checked';?> class="check"  id="c_<?=$value->id?>"></td>
        <td><span class="<?php if($value->is_striked)echo 'striked';?>" id="sp_<?=$value->id;?>" ><?=$value->description;?> &nbsp;&nbsp;&nbsp;</span>
            <input type="text" id="e_<?=$value->id;?>"  class="name" value="<?=$value->description;?>">
            <!-- <i class="fa fa-edit edit" aria-hidden="true" id="ed_<?=$value->id;?>"></i>
            <i class="fa fa-check update" aria-hidden="true" id="t_<?=$value->id;?>"></i> -->
        </td>
        <td><i class="fa fa-edit edit" aria-hidden="true" id="ed_<?=$value->id;?>" style="color:blue"></i>
            <i class="fa fa-check update" aria-hidden="true" id="t_<?=$value->id;?>" style="color:green"></i>
        </td>
        <td><i class="fa fa-trash delete" aria-hidden="true" id="d_<?=$value->id;?>" style="color:red"></i></td>
    </tr>
    <?php } ?>
    </tbody>
</table>

<!-- <?php 
        // echo '<pre>';print_r($user);
        foreach ($todos as  $key => $value){ 
    ?>


    <input type="checkbox"  <?php if($value->is_striked)echo 'checked';?> class="check"  id="c_<?=$value->id?>">
    
    <span class="<?php if($value->is_striked)echo 'striked';?>" id="sp_<?=$value->id;?>" ><?=$value->description;?> &nbsp;&nbsp;&nbsp;</span>

    
    <input type="text" id="e_<?=$value->id;?>"  class="name" value="<?=$value->description;?>">
    <i class="fa fa-edit edit" aria-hidden="true" id="ed_<?=$value->id;?>"></i>
    <i class="fa fa-check update" aria-hidden="true" id="t_<?=$value->id;?>"></i>
    <i class="fa fa-trash delete" aria-hidden="true" id="d_<?=$value->id;?>"></i>    
    <br>

<?php } ?> -->



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>


$(".update").on("click", function(){
    var fid=$(this).attr('id')
    // alert(fid)

    var id = fid.split('_');
    // alert(id[1])
    id=id[1];
    var name=$('#e_'+id).val();
    let fields = {};
    
    if(name==''){
        alert("please enter some value");
    }
    else{
        fields['description'] = name;
        fields['id'] = id;
       
        $.ajax({
            method: "Post",
            url: "/todo_update",
            data: JSON.stringify(fields)
        }).done(function(msg) {
            console.log(msg);
            reload();       
        });
    }


});

$(".check").on("change", function(){
    // alert("changed")
    var fid=$(this).attr('id')
    // alert(fid)

    var id = fid.split('_');
    // alert(id[1])
    id=id[1];
    let fields = {};
    var is_striked=0;
    if($(this).is(':checked')){
    //I am checked
        is_striked=1;
    }
    else{
        is_striked=0;
    }

    fields['id'] = id;
    fields['is_striked'] = is_striked;

    $.ajax({
        method: "Post",
        url: "/todo_strike",
        data: JSON.stringify(fields)
    }).done(function(msg) {
        console.log(msg);
        reload();       
    });


});

// $(".delete").on("click", function(){
//     var fid=$(this).attr('id');
//     // alert(fid)

//     var id = fid.split('_');
//     // alert(id[1])
//     id=id[1];
    
//     $.get('/todo/'+id+'/delete/', function(response) {
//         reload();
//     });

// });


$(".delete").click(function(){
    var fid=$(this).attr('id');
    // alert(fid)

    var id = fid.split('_');
    // alert(id[1])
    id=id[1];
    
    $.get('/todo/'+id+'/delete/', function(response) {
        reload();
    });
});










$(".edit").on("click", function(){
    var fid=$(this).attr('id')
    // alert(fid)

    var id = fid.split('_');
    // alert(id[1])
    id=id[1];
    $('#e_'+id).show();
    $('#t_'+id).show();
    $('#sp_'+id).hide();
    $('#ed_'+id).hide();

});






$(document).ready(function() {
    $('.name').hide();
    $('.update').hide();
    
});
</script>