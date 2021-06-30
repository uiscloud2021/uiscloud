<!-- Modal CREAR ARCHIVO-->
<div class="modal fade" id="createFileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createFileModalLabel">Subir archivo</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cancelar">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['autocomplete' => 'off', 'method'=>'POST', 'enctype' => 'multipart/form-data', 'id'=>'formcreate_files', 'files' => true]) !!}
      <div class="modal-body">
        
        <div class="form-group">
            {!! Form::label('name_addf', 'Nombre', ['class' => 'form-label']) !!}
            {!! Form::text('name_addf', null, ['class' => 'form-control', 'id' => 'name_addf', 'placeholder' => 'Ingrese el nombre del archivo']) !!}

        </div>

        <div class="form-group">
            {!! Form::label('archivo_addf', 'Archivo', ['class' => 'form-label']) !!}
            {!! Form::file('archivo_addf', ['class' => 'form-control-file', 'id' => 'archivo_addf', 'enctype' => 'multipart/form-data']) !!}

        </div>

        <div class="form-group">
            {!! Form::hidden('category_id_addf', null, ['class' => 'form-control', 'id'=>'category_id', 'readonly']) !!}
            {!! Form::hidden('nivel_addf', null, ['class' => 'form-control', 'id'=>'nivelfile_id', 'readonly']) !!}
            {!! Form::hidden('idfolder_addf', null, ['class' => 'form-control', 'id'=>'folderfile_id', 'readonly']) !!}
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" id="btnSubirFile" class="btn btn-primary">Subir</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>






<!-- Modal EDITAR ARCHIVO-->
<div class="modal fade" id="editFileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFileModalLabel">Editar archivo</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cancelar">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'id'=>'formedit_files', 'files' => true]) !!}
      <div class="modal-body">
        <div class="form-group">
        <?php
        $user=auth()->user()->name;
        ?>
            {!! Form::hidden('id', null, ['class' => 'form-control', 'id'=>'editfile_id']) !!}
            {!! Form::hidden('user_name', $user, ['class' => 'form-control', 'id'=>'editfile_user_name', 'readonly']) !!}

            {!! Form::label('name_editf', 'Nombre', ['class' => 'form-label']) !!}
            {!! Form::text('name_editf', null, ['class' => 'form-control', 'id'=>'editfile_name', 'placeholder' => 'Ingrese el nombre del archivo']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('archivo_editf', 'Nuevo archivo', ['class' => 'form-label']) !!}
            {!! Form::file('archivo_editf', ['class' => 'form-control-file', 'id'=>'editfile_file', 'enctype' => 'multipart/form-data']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('size_editf', 'Tamaño del archivo actual', ['class' => 'form-label']) !!}
            {!! Form::text('size_editf', null, ['class' => 'form-control', 'id'=>'editfile_size', 'placeholder' => 'Ingrese el tamaño del archivo', 'readonly']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('type_editf', 'Extensión del archivo actual', ['class' => 'form-label']) !!}
            {!! Form::text('type_editf', null, ['class' => 'form-control', 'id'=>'editfile_type', 'placeholder' => 'Ingrese el tipo de archivo', 'readonly']) !!}
        </div>

        <div class="form-group" id="div_bloqueado">
            {!! Form::label('block_editf', 'Archivo bloqueado (Si = El archivo no se podrá descargar)', ['class' => 'form-label']) !!}
            {!! Form::select('block_editf', [null => 'Seleccione'] + ['0' => 'No', '1'=>'Si'], null, ['class' => 'form-control', 'id'=>'editfile_block']) !!}
            <br/>Bloqueado por: {!! Form::text('userblock_editf', null, ['class' => 'form-control', 'id'=>'editfile_userblock', 'readonly']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('details_editf', 'Descripción (Solo si se sube un nuevo archivo)', ['class' => 'form-label']) !!}
            {!! Form::textarea('details_editf', null, ['class' => 'form-control', 'id'=>'editfile_details', 'placeholder' => 'Descripción o detalles del cambio']) !!}
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" id="btnEditFile" class="btn btn-primary">Actualizar</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>






<!-- Modal ELIMINAR ARCHIVO-->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cancelar">
                <span aria-hidden="true">&times;</span>
            </button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar el archivo?
        {!! Form::open(['id' => 'form_delete', 'method' => 'DELETE']) !!}
        {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="btnEliminar" data-bs-toggle="modal" data-bs-target="#confirmModal" name="btnEliminar" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>





<!-- Modal CREAR CARPETA-->
<div class="modal fade" id="createFolderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createFolderModalLabel">Crear carpeta</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cancelar">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['autocomplete' => 'off', 'method' => 'POST', 'id'=>'formcreate_folder']) !!}
      <div class="modal-body">
        
        <div class="form-group">
            {!! Form::label('name_addc', 'Nombre', ['class' => 'form-label']) !!}
            {!! Form::text('name_addc', null, ['class' => 'form-control', 'id'=>'addfolder_name', 'placeholder' => 'Ingrese el nombre de la carpeta']) !!}
        </div>

        <div class="form-group">
            {!! Form::hidden('nivel_addc', null, ['class' => 'form-control', 'id'=>'nivelfolder_id', 'readonly']) !!}
            {!! Form::hidden('url_addc', null, ['class' => 'form-control', 'id'=>'addfolder_url', 'readonly']) !!}
            {!! Form::hidden('category_id_addc', null, ['class' => 'form-control', 'id'=>'categoryfolder_id', 'readonly']) !!}
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" id="btnSubirFolder" class="btn btn-primary">Crear</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>






<!-- Modal EDITAR CARPETA-->
<div class="modal fade" id="editFolderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFolderModalLabel">Editar carpeta</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cancelar">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['method' => 'POST', 'id'=>'formedit_folder']) !!}
      <div class="modal-body">
        <div class="form-group">
            {!! Form::hidden('id', null, ['class' => 'form-control', 'id'=>'editfolder_id']) !!}
            
            {!! Form::label('name_editc', 'Nombre', ['class' => 'form-label']) !!}
            {!! Form::text('name_editc', null, ['class' => 'form-control', 'id'=>'editfolder_name', 'placeholder' => 'Ingrese el nombre de la carpeta']) !!}
        </div>

        <div class="form-group">
            {!! Form::hidden('nivel_editc', null, ['class' => 'form-control', 'id'=>'nivelfolderedit_id', 'readonly']) !!}
            {!! Form::hidden('category_id_editc', null, ['class' => 'form-control', 'id'=>'categoryfolderedit_id', 'readonly']) !!}
            
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" id="btnEditFolder" class="btn btn-primary">Actualizar</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>