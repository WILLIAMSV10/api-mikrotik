<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <!-- Link Styles -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fuentes.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <div class="logo_details">
            <div class="logo_name">Mikrotik</div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="/api/mikrotik/users">
                    <i class="fa-solid fa-user"></i>
                    <span class="link_name">Usuarios</span>
                </a>
                <span class="tooltip">Usuarios</span>
            </li>
            <li>
                <a href="/api/mikrotik/interfaces">
                    <i class="fa-regular fa-hard-drive"></i>
                    <span class="link_name">Interfaces</span>
                </a>
                <span class="tooltip">Interfaces</span>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-globe"></i>
                    <span class="link_name">ip</span>
                </a>
                <span class="tooltip">ip</span>
            </li>
            <li>
                <a href="/plane_sections">
                    <i class="fa-solid fa-wifi"></i>
                    <span class="link_name">Ancho de banda</span>
                </a>
                <span class="tooltip">Ancho de banda</span>
            </li>
        </ul>
    </div>


    <section class="home-section">
        <main>
            @if ($action === 'list')
                <div class="presentacion">
                    <div class="presContent">
                        <p>Hola {{ $userName ? $userName : 'Undefined' }}</p>
                        <p>Bienvenido a tu server de Mikrotik</p>
                        <p>Que deseas hacer hoy?</p>
                        <p>Izi no Izi?</p>
                    </div>
                </div>
                <a href="{{ route('mikrotik.user.create') }}">Crear Nuevo</a>
                @isset($datas)
                    <div class="container">
                        @foreach ($datas as $data)
                            <div class="content">
                                <div class="text montserratFont">
                                    @foreach ($data as $key => $value)
                                        <p>{{ $key }}: {{ $value != '' ? $value : 'undefined' }}</p>
                                    @endforeach
                                    <form action="{{ route('mikrotik.user.edit', $data['.id']) }}" method="GET"><button type="submit">Editar</button></form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endisset
            @elseif ($action === 'edit')
                <div class="area">
                    <form action="{{ route('mikrotik.user.update', $data['.id']) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Esto le indica a Laravel que debe tratar la solicitud como PUT -->
                        @foreach ($fields as $field_type => $field_list)
                            @if ($field_type === 'read_fields')
                                @foreach ($field_list as $field)
                                    <p>{{ $field }}: {{ isset($data[$field]) ? $data[$field] : 'undefined' }}</p>
                                @endforeach
                            @elseif ($field_type === 'write_fields')
                                @foreach ($field_list as $field)
                                    <label>{{ $field }}: </label>
                                    <input name={{ $field }} value="{{ $data[$field] }}">
                                @endforeach
                            @elseif ($field_type === 'option_fields')
                                @foreach ($field_list as $field)
                                    @foreach ($relations as $relation_type => $relation_value)
                                        @if ($relation_type === $field)
                                            @php
                                                $current_relation = [$relation_type => $relation_value];
                                                $specific_relation = $current_relation[$field];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <label>{{ $field }}: </label>
                                    <select name={{ $field }}>
                                        @foreach ($specific_relation as $rel)
                                            <option value={{ $rel['name'] }} {{ $rel['name'] === $data[$field] ? 'selected' : '' }}>{{ $rel['name'] }}</option>
                                        @endforeach
                                    </select>
                                @endforeach
                            @elseif ($field_type === 'boolean_fields')
                                @foreach ($field_list as $field)
                                    <label>{{ $field }}</label>
                                    <select name={{ $field }}>
                                        <option value="true" {{ $data[$field] === 'true' ? 'selected' : '' }}>true</option>
                                        <option value="false" {{ $data[$field] === 'false' ? 'selected' : '' }}>false</option>
                                    </select>
                                @endforeach
                            @endif
                        @endforeach
                        <button type="submit">Save Changes</button>
                        @if (session('mensaje'))
                            <div class="alert alert-danger">
                                {{ session('mensaje') }}
                            </div>
                        @endif
                    </form>
                </div>
            @elseif ($action === 'create')
                <div class="area">
                    <form action="{{ route('mikrotik.user.store') }}" method="POST">
                        @csrf
                        @foreach ($fields as $field_type => $field_list)
                            @if ($field_type === 'write_fields')
                                @foreach ($field_list as $field)
                                    <label>{{ $field }}: </label>
                                    <input name={{ $field }}>
                                @endforeach
                            @endif
                            @if ($field_type === 'option_fields')
                                @foreach ($field_list as $field)
                                    @foreach ($relations as $relation_type => $relation_value)
                                        @if ($relation_type === $field)
                                            @php
                                                $current_relation = [$relation_type => $relation_value];
                                                $specific_relation = $current_relation[$field];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <label>{{ $field }}: </label>
                                    <select name={{ $field }}>
                                        @foreach ($specific_relation as $rel)
                                            <option value={{ $rel['name'] }}>{{ $rel['name'] }}</option>
                                        @endforeach
                                    </select>
                                @endforeach
                            @endif
                            @if ($field_type === 'boolean_fields')
                            @endif
                        @endforeach
                        <button type="submit"> Crear</button>
                    </form>
                </div>
            @endif
        </main>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fromSelect = document.getElementById('from_id');
            const toSelect = document.getElementById('to_id');

            function checkSelection() {
                if (fromSelect.value === toSelect.value) {
                    toSelect.selectedIndex = 0; // Cambia al primer elemento
                }
            }

            fromSelect.addEventListener('change', checkSelection);
            toSelect.addEventListener('change', checkSelection);
        });
    </script>
    </section>


    <!-- Scripts -->
    <script>
        window.onload = function() {
            const sidebar = document.querySelector(".sidebar");
            const closeBtn = document.querySelector("#btn");
            const searchBtn = document.querySelector(".bx-search")

            closeBtn.addEventListener("click", function() {
                sidebar.classList.toggle("open")
                menuBtnChange()
            })

            searchBtn.addEventListener("click", function() {
                sidebar.classList.toggle("open")
                menuBtnChange()
            })

            function menuBtnChange() {
                if (sidebar.classList.contains("open")) {
                    closeBtn.classList.replace("bx-menu", "bx-menu-alt-right")
                } else {
                    closeBtn.classList.replace("bx-menu-alt-right", "bx-menu")
                }
            }
        }
    </script>

</body>

</html>
