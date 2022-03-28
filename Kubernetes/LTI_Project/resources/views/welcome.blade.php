<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kubernetes or OpenStack API</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    @yield('extrastyles')
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <script src="https://use.fontawesome.com/1bce9209b1.js"></script>


    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/adminlte/plugins/summernote/summernote-bs4.css">
{{--    <link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">--}}

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <style media="screen">

        .login-container{
            margin-top: 5%;
            margin-bottom: 5%;
        }
        .login-logo{
            position: relative;
            margin-left: -41.5%;
        }
        .login-logo img{
            position: absolute;
            width: 20%;
            margin-top: 19%;
            background: #282726;
            border-radius: 4.5rem;
            padding: 5%;
        }
        .login-form-1{
            padding: 9%;
            background:#ffffff;
            box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
        }
        .login-form-1 h3{
            text-align: center;
            margin-bottom:12%;
            color:#000000;
        }
        .btnSubmit{
            font-weight: 600;
            width: 50%;
            color: #ffffff;
            background-color: #c41616;
            border: none;
            border-radius: 1.5rem;
            padding:2%;
        }
        .btnForgetPwd{
            color: #fff;
            font-weight: 600;
            text-decoration: none;
        }
        .btnForgetPwd:hover{
            text-decoration:none;
            color:#fff;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        body {
            overflow-x: hidden;
        }

        .form-control {
            height: auto !important;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            -webkit-transition: margin .25s ease-out;
            -moz-transition: margin .25s ease-out;
            -o-transition: margin .25s ease-out;
            transition: margin .25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 15rem;
        }

        #page-content-wrapper {
            min-width: 100vw;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }
        }
    </style>

</head>

<body>

<div class="d-flex" id="app">
    <div  v-if="this.$store.state.token" class="bg-light border-right" id="sidebar-wrapper">
        <div v-if="this.$store.state.projeto == 'openstack'" class="sidebar-heading"><router-link to="/dashboard" class="navbar-brand"><img src="{{URL::asset('/images/logo.png')}}" alt="some text" width=200 height=100/></router-link></div>
        <div v-if="this.$store.state.projeto == 'kubernetes'" class="sidebar-heading"><router-link to="/dasboardKubernetes" class="navbar-brand"><img src="{{URL::asset('/images/logoLoginKub.png')}}" alt="some text" width=200 height=100/></router-link></div>
        <div v-if="this.$store.state.projeto == 'openstack'" class="list-group list-group-flush">
            <p>
                <a class="list-group-item list-group-item-action btn-outline-secondary  dropdown-toggle" data-toggle="collapse" href="#multiCollapseProject" role="button" aria-expanded="false" aria-controls="multiCollapseProject"
                   role="button" aria-pressed="true">Project</a>
            </p>
            <div class="row">
                <div class="col">
                    <div class="collapse multi-collapse" id="multiCollapseProject">
                        <p>
                            <a class="list-group-item list-group-item-action bg-light dropdown-toggle" data-toggle="collapse" href="#multiCollapseCompute" role="button" aria-expanded="false" aria-controls="multiCollapseCompute" role="button" aria-pressed="true"
                               style="text-align:center">Compute</a>
                        </p>
                        <div class="row">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseCompute">
                                    <div>
                                        <router-link to="/instancias" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Instances</router-link>
                                        <router-link to="/imagens" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Images</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>
                            <a class="list-group-item list-group-item-action bg-light dropdown-toggle" data-toggle="collapse" href="#multiCollapseVolumes" role="button" aria-expanded="false" aria-controls="multiCollapseVolumes" role="button" aria-pressed="true" style="text-align:center">Volumes</a>
                        </p>
                        <div class="row">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseVolumes">
                                    <div>
                                        <router-link to="/volumes" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Volumes</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>
                            <a class="list-group-item list-group-item-action bg-light dropdown-toggle" data-toggle="collapse" href="#multiCollapseContainers" role="button" aria-expanded="false" aria-controls="multiCollapseVolumes" role="button" aria-pressed="true" style="text-align:center">Containers</a>
                        </p>
                        <div class="row">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseContainers">
                                    <div>
                                        <router-link to="/containers" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Containers</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="this.$store.state.projeto == 'kubernetes'" class="list-group list-group-flush">
            <p>
                <a class="list-group-item list-group-item-action btn-outline-secondary  dropdown-toggle" data-toggle="collapse" href="#multiCollapseProject" role="button" aria-expanded="false" aria-controls="multiCollapseProject"
                   role="button" aria-pressed="true">Project</a>
            </p>
            <div class="row">
                <div class="col">
                    <div class="collapse multi-collapse" id="multiCollapseProject">
                        <p>
                            <a class="list-group-item list-group-item-action bg-light dropdown-toggle" data-toggle="collapse" href="#multiCollapseCompute" role="button" aria-expanded="false" aria-controls="multiCollapseCompute" role="button" aria-pressed="true"
                               style="text-align:center">Cluster</a>
                        </p>
                        <div class="row">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseCompute">
                                    <div>
                                        <router-link to="/namespaces" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Namespaces</router-link>
                                        <router-link to="/nodes" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Nodes</router-link>
                                        <router-link to="/clusterRoles" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Cluster Roles</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>
                            <a class="list-group-item list-group-item-action bg-light dropdown-toggle" data-toggle="collapse" href="#multiCollapseVolumes" role="button" aria-expanded="false" aria-controls="multiCollapseVolumes" role="button" aria-pressed="true" style="text-align:center">Workload</a>
                        </p>
                        <div class="row">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseVolumes">
                                    <div>
                                        <router-link to="/pods" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Pods</router-link>
                                        <router-link to="/deployments" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Deployments</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>
                            <a class="list-group-item list-group-item-action bg-light dropdown-toggle" data-toggle="collapse" href="#multiCollapseContainers" role="button" aria-expanded="false" aria-controls="multiCollapseVolumes" role="button" aria-pressed="true" style="text-align:center">Services</a>
                        </p>
                        <div class="row">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseContainers">
                                    <div>
                                        <router-link to="/services" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Services</router-link>
                                        <router-link to="/endpoints" href="#" class="list-group-item list-group-item-action bg-light" style="text-align:right">Endpoints</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="this.$store.state.projeto == 'openstack'" id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">


            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li v-if="this.$store.state.token" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-html="this.$store.state.projectName">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <div v-for="project in this.$store.state.projectNames" :key="project.id">
                                <router-link :to="{name: 'changeProject', params: {projectName: project.name}}" class="dropdown-item" v-html="project.name" style="text-align:center" href="#"></router-link>
                            </div>
                        </div>

                    </li>
                    <li v-if="!this.$store.state.token" class="nav-item active">
                        <router-link class="nav-link" onclick="definirIP()" to="/login">Definir IP</router-link>
                    </li>
                    <li v-if="!this.$store.state.token" class="nav-item active">
                        <router-link class="nav-link" to="/login">Login</router-link>
                    </li>
                    <li v-else class="nav-item active">
                        <router-link class="nav-link" to="/logout">Logout</router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <router-view>@yield('content')</router-view>
        </div>
    </div>
    <div v-if="this.$store.state.projeto == 'kubernetes'" id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">


            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li v-if="this.$store.state.token!='no'" class="nav-item active">
                        <router-link class="nav-link" onclick="definirIP()" to="/kuberneteslogin">Definir IP</router-link>
                    </li>
                    <li v-if="this.$store.state.token!='no'" class="nav-item active">
                        <router-link class="nav-link" to="/kuberneteslogin">Login</router-link>
                    </li>
                    <li v-else class="nav-item active">
                        <router-link class="nav-link" to="/logout">Logout</router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <router-view>@yield('content')</router-view>
        </div>
    </div>
</div>
@yield('pagescript')
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript">
    function definirIP() {
        document.getElementById("ipServer").click();
    }
</script>

</body>
<script src="js/app.js"> </script>

</html>
