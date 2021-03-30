<div class="form-group">
    {!! Form::label('name', 'Nombre', ['class' => 'form-label']) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del directorio']) !!}
                
    @error('name')
        <span class="text-danger">{{$message}}</span>
    @enderror
</div>

<div class="form-group">
    <p class="font-weight-bold">Usuarios que tendrán acceso</p>
    @foreach ($users as $user)
        <label class="mr-2">
        {!! Form::checkbox('users[]', $user->id, null) !!}
        {{$user->name}}
        </label><br/>
    @endforeach

    @error('users')
        <span class="text-danger">{{$message}}</span>
    @enderror
</div>