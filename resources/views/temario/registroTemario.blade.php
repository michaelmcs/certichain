@extends('layouts/app')
@section('titulo', 'registrar temas')

@section('content')


    {{-- notificaciones --}}




    <h4 class="text-center text-secondary">REGISTRO DE CURSOS</h4>

    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    @if (session('DUPLICADO'))
        <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('DUPLICADO') }}</div>
    @endif


    <div class="mb-0 col-12 bg-white p-5">
        <form action="{{ route('temario.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">

                <div class="fl-flex-label mb-5 col-12 col-lg-12">
                    <select name="curso" class="input input__text">
                        <option value="">Seleccionar curso...</option>
                        @foreach ($curso as $item)
                            <option value="{{ $item->id_curso }}" {{ old('curso') == $item->id_curso ? 'selected' : '' }}>
                                {{ $item->nombre }}</option>
                        @endforeach
                    </select>
                    @error('curso')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fl-flex-label mb-5 col-12 col-lg-12">
                    <input type="text" name="tema" class="input input__text" id="tema"
                        placeholder="Tema a tratar" value="{{ old('tema') }}">
                    @error('tema')
                        <small class="error error__text">{{ $message }}</small>
                    @enderror
                </div>


                <div class="text-right mt-0">
                    <a href="{{ route('temario.index') }}" class="btn btn-rounded btn-secondary m-2">Atras</a>
                    <button type="submit" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </div>

        </form>
    </div>




@endsection
