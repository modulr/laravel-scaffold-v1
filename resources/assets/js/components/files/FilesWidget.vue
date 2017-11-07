<template>
    <div class="files">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Layout List -->
            <div class="row" v-if="files.length">
                <div class="col-md-12">
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
            <!-- Init Message  -->
            <div class="init-message" v-if="files.length == 0">
                <i class="mdi mdi-folder" aria-hidden="true"></i>
                <p class="lead">Upload or create your first file/folder!!</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
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
        }
    }
</script>
