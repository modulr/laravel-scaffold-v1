<template>
    <div class="files">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-link" @click.prevent="toggleLayout">
                            <i class="fa fa-fw fa-lg" aria-hidden="true"
                               :class="{'fa-list': layout == 'list', 'fa-th-large': layout == 'grid'}"
                            ></i>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" v-model="search">
                            <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- List & Info -->
            <div class="row">
                <!-- List Files -->
                <div class="col-md-12">
                    <!-- Layout List -->
                    <div class="row" v-show="layout == 'list'" v-if="files.length">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-hover table-ellipsis">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th class="hidden-xs">Modified</th>
                                                <th class="hidden-xs">Size</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(file, index) in filteredFiles">
                                                <td>
                                                    <a href="#" @click.prevent="openFile(file)">
                                                        <i class="fa fa-fw fa-folder-o fa-2x" v-if="file.is_folder"></i>
                                                        <img :src="file.url" class="media-object" v-if="!file.is_folder && file.type.match('image/*')">
                                                        <i class="fa fa-fw fa-file-o fa-2x" v-if="!file.is_folder && !file.type.match('image/*')"></i>
                                                        {{file.name}}
                                                    </a>
                                                </td>
                                                <td class="hidden-xs">{{file.updated_at | moment('from')}}</td>
                                                <td class="hidden-xs">
                                                    <span v-if="file.size">{{file.size | prettyBytes}}</span>
                                                    <span v-else>---</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Layout Grid -->
                    <div class="row" v-show="layout == 'grid'" v-if="files.length">
                        <div class="col-xs-6 col-sm-4 col-md-3" v-for="(file, index) in filteredFiles">
                            <div class="thumbnail">
                                <a href="#" class="thumbnail-heading">
                                    <i class="fa fa-fw fa-folder-o" v-if="file.is_folder"></i>
                                    <img :src="file.url" class="img-responsive" v-if="!file.is_folder && file.type.match('image/*')">
                                    <i class="fa fa-fw fa-file-o" v-if="!file.is_folder && !file.type.match('image/*')"></i>
                                </a>
                                <div class="caption">
                                    <a href="#" @click.prevent="openFile(file)">
                                        <p :title="file.name">{{file.name}}</p>
                                        <small class="text-muted" v-if="file.size">{{file.size | prettyBytes}}</small>
                                        <small class="text-muted" v-else>---</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Init Message  -->
                    <div class="init-message" v-if="files.length == 0">
                        <i class="mdi mdi-folder" aria-hidden="true"></i>
                        <p class="lead">Upload or create your first file/folder!!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: null,
                layout: 'list',

                files: [],

                folderCurrentId: 0,
            }
        },
        mounted() {
            var str = window.location.pathname;
            var res = str.split("/");

            if (res[2] == 'folder') {
                this.folderCurrentId = parseInt(res[3]);
                axios.get('/files/byUser/' + this.folderCurrentId)
                .then(response => {
                    this.files = response.data;
                });
            } else {
                axios.get('/files/byUser')
                .then(response => {
                    this.files = response.data;
                });
            }

            if (JSON.parse(localStorage.getItem('files'))) {
                this.layout = JSON.parse(localStorage.getItem('files')).layout;
            }
        },
        computed: {
            filteredFiles: function () {
                var filteredArray = this.files,
                    search = this.search;

                if(!search){
                    return filteredArray;
                }

                search = search.trim().toLowerCase();

                filteredArray = filteredArray.filter(function(item){
                    return Object.keys(item).some(function (key) {
                        return String(item[key]).toLowerCase().indexOf(search) !== -1
                    })
                })

                return filteredArray;;
            }
        },
        methods: {
            openFile (file) {
                if (file.is_folder) {
                    location.href = '/files/folder/' + file.id;
                } else {
                    location.href = file.url;
                }
            },
            toggleLayout () {
                this.layout == 'list' ? this.layout = 'grid' : this.layout = 'list';
                localStorage.setItem('files', JSON.stringify({'layout':this.layout}));
            },
        }
    }
</script>
