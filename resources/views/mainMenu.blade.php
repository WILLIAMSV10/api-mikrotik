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
                <a href="">
                    <i class="fa-solid fa-user"></i>
                    <span class="link_name">Usuarios</span>
                </a>
                <span class="tooltip">Usuarios</span>
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
            <div class="presentacion">
                <div class="presContent">
                    <p>Hola "Usuario"</p>
                    <p>Bienvenido a tu server de Mikrotik</p>
                    <p>Que deseas hacer hoy?</p>
                    <p>Izi no Izi?</p>
                </div>
            </div>
            @isset($datas)
                @foreach ($datas as $data)
                    <div class="container">
                        <div class="content">
                            <div class="text">
                                @foreach ($data as $key => $value)
                                    <p>{{ $key }}: {{ $value != '' ? $value : 'undefined' }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset
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
