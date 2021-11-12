$(document).ready(function() {
    List();
} );

var file_delete;

function Directorios(datos){
    var fold = datos.split(",");
    folder_name=fold[0];
    nivel=fold[1];
    $('#id_folder').val("");
    nivel=parseInt(nivel)-1;
    $('#nivel_folder').val(nivel);
    $('#name_fold').val(folder_name);
    $('#form_details').submit();
}

//TABLA PARA DESCARGAR ARCHIVO
function DescargarFile(file_id){
    $.ajax({
        url: "/dashboard/download_files",
        method:'POST',
        dataType: 'json',
        data: {
            file_id:file_id,
            _token:$('input[name="_token"]').val()
        },
        success: function (resp)
        { 
            if(resp.success == "disponible"){
                window.open(resp.url, "_blank");
            }
            if(resp.success == "disponible"){
                window.open(resp.url, "_blank");
                location.reload();
            }else if(resp.success == "bloqueadoedit"){
                setTimeout(function(){
                    toastr.warning('Subir el archivo actualizado o desbloquear el archivo', 'Archivo en uso', {timeOut:3000});
                });
                EditFile(resp.id);
            }else if(resp.success == "bloqueado"){
                setTimeout(function(){
                    toastr.warning('El archivo se encuentra en uso por el usuario '+resp.user, 'Archivo en uso', {timeOut:3000});
                });
            }
        }   
    });
}



//BOTONES SELECCIONADOS
$('input[type=checkbox]').on('change', function() {
    total = $('input[type=checkbox]:checked').length;
    if(total == 0){
        $( "#downloadb" ).hide();
        //$( "#downloadone" ).hide();
        $( "#editb" ).hide();
        $( "#deleteb" ).hide();
    }else if(total == 1){
        $( "#downloadb" ).hide();
        //$( "#downloadone" ).show();
        $( "#editb" ).show();
        $( "#deleteb" ).show();
    }else{
        $( "#downloadb" ).show();
        //$( "#downloadone" ).hide();
        $( "#editb" ).hide();
        $( "#deleteb" ).hide();
    }
});

function Comprimir(){
    var id = [];
    $('input[type=checkbox]:checked').each(function() {
      id.push($(this).val());
    });
    if(id.length > 0){
        toastr.warning('Los archivos están siendo procesados para descarga', 'Descargar', {timeOut:3000});
        icons=$('#cont_icons').val();
        for(a=1; a<=icons; a++){
            $("#chk_icons"+a).prop('checked', false);
        }
        $.ajax({
            url: "/dashboard/comprimir_files",
            method: "post",
            data: {
                id:id,
                _token:$('input[name="_token"]').val()
            },
            success: function (resp)
            {
                window.open(resp, "_blank");
            }   
    });
    }else{
        toastr.warning('Seleccionar al menos un archivo', 'Descargar', {timeOut:3000});
    }
}

function EditB(){
    var id = [];
    $('input[type=checkbox]:checked').each(function() {
      id.push($(this).val());
    });
    document.getElementById("div_size").style.display="none";
    document.getElementById("div_extension").style.display="none";
    if(id.length == 1){
        EditFile(id);
    }else{
        toastr.warning('Seleccionar solo un archivo', 'Editar', {timeOut:3000});
    }
}

function DeleteB(){
    var id = [];
    $('input[type=checkbox]:checked').each(function() {
      id.push($(this).val());
    });
    if(id.length == 1){
        file_delete = id;
        $('#confirmModal').modal('show');
    }else{
        toastr.warning('Seleccionar solo un archivo', 'Eliminar', {timeOut:3000});
    }
}

function DownloadB(){
    var id = [];
    $('input[type=checkbox]:checked').each(function() {
      id.push($(this).val());
    });
    if(id.length == 1){
        DescargarFile(id);
    }else{
        toastr.warning('Seleccionar solo un archivo', 'Descargar', {timeOut:3000});
    }
}
//FIN DE BOTONES SELECCIONADOS

function Datatable_list(){
    id_category=$('#id_category').val();
    id_folder=$('#idppal_folder').val();
    nivel=$('#nivel_folder').val();
    var list_files = $('#list').DataTable({
        dom: 'T<"clear">lfrtip',
        "processing": true,
        "serverSide": true,
        destroy: true,
        "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
        "ajax":{
            "url": "/dashboard/list_files",
            "method": "POST",
            "data": {
                id_category:id_category,
                id_folder:id_folder,
                nivel:nivel,
                _token:$('input[name="_token"]').val()
            },
            
        },
        "columns": [
            {"data": 'img'},
            {"data": 'name'},
            {"data": 'date'},
            {"data": 'size'},
            {"data": 'edit'},
            {"data": 'delete'},
        ],
        "language": espanol
    });
}

//TABLA DE LISTA DE ARCHIVOS
function List(){
    $('#icons').hide(500);
    $('#lists').show(1500);
    $("#view").removeClass("fas fa-table");
    $("#view").addClass("fas fa-list");
    Datatable_list();
}

function Icon(){
    $('#lists').hide(500);
    $('#icons').show(1500);
    $("#view").removeClass("fas fa-list");
    $("#view").addClass("fas fa-table");
}

$(document).on('click', '.delete', function(event){
    event.preventDefault();
    file_delete = $(this).attr('id');
    $('#confirmModal').modal('show');
})

$('#btnEliminar').click(function(){
    $.ajax({
        url: "/dashboard/delete_files",
        type:'POST',
        data: {
            id:file_delete,
            _token:$('input[name="_token"]').val()
        },
        beforeSend:function(){
            $('#btnEliminar').text('Eliminando...');
        },
        success: function (resp)
        {
            if(resp == "eliminado"){
                setTimeout(function(){
                    $('#confirmModal').modal('hide');
                    toastr.success('El archivo fue eliminado correctamente', 'Eliminar archivo', {timeOut:3000});
                    List();
                }, 2000);
            }
        }   
    });
})

function EditFile(id){
    $.ajax({
        url: "/dashboard/edit_files",
        method:'POST',
        dataType: 'json',
        data: {
            id:id,
            _token:$('input[name="_token"]').val()
        },
        success: function (data)
        {
            var posts = JSON.parse(data);
            document.getElementById("btnEditFile").style.display="";
            $.each(posts, function() {
                $('#editfile_id').val(this.id);
                $('#editfile_name').val(this.name);
                $('#editfile_size').val(this.size);
                $('#editfile_type').val(this.type);
                $('#editfile_block').val(this.bloqueado);
                $('#editfile_userblock').val(this.user_block);

                if(this.bloqueado == '0'){
                    document.getElementById("div_bloqueado").style.display="none";
                    $('#editfile_block').attr("disabled", true);
                    document.getElementById("div_archivo").style.display="none";
                    $('#editfile_name').attr("disabled", false);
                    document.getElementById("div_details").style.display="none";
                }else{
                    document.getElementById("div_bloqueado").style.display="";
                    if($('#editfile_user_name').val() == this.user_block){
                        $('#editfile_block').attr("disabled", false);
                    }else{
                        $('#editfile_block').attr("disabled", true);
                        document.getElementById("btnEditFile").style.display="none";
                    }
                    document.getElementById("div_archivo").style.display="";
                    document.getElementById("div_size").style.display="none";
                    document.getElementById("div_extension").style.display="none";
                    $('#editfile_name').attr("disabled", true);
                    document.getElementById("div_details").style.display="";
                }
            });
            $('#editFileModal').modal('toggle');
        }   
    });
}

$('#formedit_files').on('submit', function(e) {
    e.preventDefault();
    $('#editfile_block').attr("disabled", false);
    var formData = new FormData(this);
    formData.append('_token', $('input[name=_token]').val());
    name_arch=$('#editfile_name').val();
    if(name_arch!=""){
        $.ajax({
            url: "/dashboard/update_files",
            type:'post',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('#btnEditFile').hide();
                $('#editFileModal').modal('hide');
                $('#overlay').show();
            },
            success:function(resp){
                if(resp == "actualizado"){
                    setTimeout(function(){
                    toastr.success('El archivo fue modificado correctamente', 'Actualizar archivo', {timeOut:3000});
                    location.reload();
                    });
                } else if(resp == "desigual"){
                    setTimeout(function(){
                    toastr.warning('El archivo no corresponde al que se descargo', 'Actualizar archivo', {timeOut:3000});
                    $('#overlay').hide();
                    //location.reload();
                    });
                }
            }
        });
    }else{
        alert("No puede estar el nombre del archivo vacío");
    }
});


function CreateFile(){
    $('#createFileModal').modal('toggle');
    category=$('#id_category').val();
    $('#category_id').val(category);
    nivel=$('#nivel_folder').val();
    $('#nivelfile_id').val(nivel);
    id_folder=$('#idppal_folder').val();
    $('#folderfile_id').val(id_folder);
}

function CreateZIP(){
    $('#createZIPModal').modal('toggle');
    category=$('#id_category').val();
    $('#categoryzip_id').val(category);
    nivel=$('#nivel_folder').val();
    $('#nivelzip_id').val(nivel);
    id_folder=$('#idppal_folder').val();
    $('#folderzip_id').val(id_folder);
}

$('#formcreate_files').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('_token', $('input[name=_token]').val());
    name_arch=$('#name_addf').val();
    archivo=$('#archivo_addf').val();
    if(name_arch!="" && archivo!=""){
        $.ajax({
            url: "/dashboard/created_files",
            type:'post',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('#btnSubirFile').hide();
                $('#createFileModal').modal('hide');
                $('#overlay').show();
            },
            success:function(resp){
                if(resp == "guardado"){
                    setTimeout(function(){
                    toastr.success('El archivo fue subido correctamente', 'Subir archivo', {timeOut:3000});
                    location.reload();
                    });
                }
            }
        });
    }else{
        alert("No puede estar ningun campo vacío");
    }
});


$('#formcreate_zip').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('_token', $('input[name=_token]').val());
    archivo=$('#archivo_addz').val();
    if(archivo!=""){
        $.ajax({
            url: "/dashboard/created_zip",
            type:'post',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('#btnSubirZIP').hide();
                $('#createZIPModal').modal('hide');
                $('#overlay').show();
            },
            success:function(resp){
                if(resp == "guardado"){
                    setTimeout(function(){
                    toastr.success('Los archivos fueron subidos correctamente', 'Subir archivos', {timeOut:3000});
                    location.reload();
                    });
                }
            }
        });
    }else{
        alert("No puede estar el archivo vacío");
    }
});


function DashSubmit(id_folder){
    $('#idppal_folder').val(id_folder);
    $('#id_folder').val(id_folder);
    $('#form_details').submit();
}

//FOLDER
function CreateFolder(){
    $('#createFolderModal').modal('toggle');
    category=$('#id_category').val();
    $('#categoryfolder_id').val(category);
    nivel=$('#nivel_folder').val();
    url=$('#urlppal_folder').val();
    $('#nivelfolder_id').val(nivel);
    $('#addfolder_url').val(url);
}


$('#formcreate_folder').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('_token', $('input[name=_token]').val());
    name_folder=$('#addfolder_name').val();
    if(name_folder!=""){
        $.ajax({
            url: "/dashboard/created_folder",
            type:'post',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('#btnSubirFolder').hide();
                $('#createFolderModal').modal('hide');
                $('#overlay').show();
            },
            success:function(resp){
                if(resp == "guardado"){
                    setTimeout(function(){
                    toastr.success('La carpeta fue creada correctamente', 'Crear carpeta', {timeOut:3000});
                    location.reload();
                    });
                }
            }
        });
    }else{
        alert("No puede estar el nombre de la carpeta vacía");
    }
});



function EditFolder(id){
    category=$('#id_category').val();
    $('#categoryfolderedit_id').val(category);
    nivel=$('#nivel_folder').val();
    $('#nivelfolderedit_id').val(nivel);
    $.ajax({
        url: "/dashboard/edit_folder",
        method:'POST',
        dataType: 'json',
        data: {
            id:id,
            _token:$('input[name="_token"]').val()
        },
        success: function (data)
        {
            var posts = JSON.parse(data);
            document.getElementById("btnEditFolder").style.display="";
            $.each(posts, function() {
                $('#editfolder_id').val(this.id);
                $('#editfolder_name').val(this.name);
            });
            $('#editFolderModal').modal('toggle');
        }   
    });
}



$('#formedit_folder').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('_token', $('input[name=_token]').val());
    name_fold=$('#editfolder_name').val();
    if(name_fold!=""){
        $.ajax({
            url: "/dashboard/update_folder",
            type:'post',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('#btnEditFolder').hide();
                $('#editFolderModal').modal('hide');
                $('#overlay').show();
            },
            success:function(resp){
                alert(resp);
                /*if(resp == "actualizado"){
                    setTimeout(function(){
                    toastr.success('La carpeta fue modificada correctamente', 'Actualizar carpeta', {timeOut:3000});
                    location.reload();
                    });
                }else{
                    toastr.warning('La carpeta no puede ser modificada porque existen archivos en su contenido', 'Actualizar carpeta', {timeOut:3000});
                }*/
            }
        });
    }else{
        alert("No puede estar el nombre de la carpeta vacía");
    }
});








let espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
        "copy": "Copiar",
        "colvis": "Visibilidad"
    }
};