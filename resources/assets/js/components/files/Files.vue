<template>
    <div class="files">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-3 controls">
                        <input type="text" class="form-control" placeholder="Search" v-model="search">
                    </div>
                    <div class="col-sm-9 text-right controls">
                        <div class="btn-group">
                            <button class="btn btn-default" :disabled="!fileCurrent.name"
                                @click.prevent="comingSoon"
                                v-if="user.hasPermission['update-files']">
                                <i class="fa fa-fw fa-arrows" aria-hidden="true"></i> Move
                            </button>
                            <button class="btn btn-default" :disabled="!fileCurrent.name"
                                @click.prevent="comingSoon"
                                v-if="user.hasPermission['update-files']">
                                <i class="fa fa-fw fa-clone" aria-hidden="true"></i> Copy
                            </button>
                            <button class="btn btn-default" :disabled="!fileCurrent.name"
                                @click.prevent="destroyFile"
                                v-if="user.hasPermission['delete-files']">
                                <i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete
                            </button>
                        </div>
                        <span class="separator"></span>
                        <a href="#" class="btn btn-default" @click.prevent="toggleLayout">
                            <i class="fa fa-fw fa-lg" aria-hidden="true"
                                :class="{'fa-list': layout == 'list', 'fa-th-large': layout == 'grid'}">
                            </i>
                        </a>
                        <span class="separator"></span>
                        <a href="#" class="btn btn-success"
                            v-if="user.hasPermission['create-files']"
                            @click.prevent="newFolder">
                            <i class="mdi mdi-create-new-folder mdi-lg"></i> New folder
                        </a>
                        <vue-clip class="vue-clip-btn"
                            :options="uploadFileOptions"
                            :on-sending="uploadFileSending"
                            :on-complete="uploadFileComplete"
                            v-if="user.hasPermission['create-files']">
                            <template slot="clip-uploader-action">
                                <div>
                                    <div class="dz-message btn btn-success">
                                        <i class="mdi mdi-file-upload mdi-lg"></i> Upload
                                    </div>
                                </div>
                            </template>
                        </vue-clip>
                    </div>
                </div>
            </div>
            <!-- List & Info -->
            <div class="row">
                <!-- List Files -->
                <div class="col-md-9">
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
                                            <tr :class="{'info': file.id == fileCurrent.id}" v-for="(file, index) in filteredFiles">
                                                <td @click="infoFile(file, index)">
                                                    <a href="#" @click.prevent="openFile(file)">
                                                        <i class="fa fa-fw fa-folder-o fa-2x" v-if="file.is_folder"></i>
                                                        <img :src="file.urlThumbnail" class="media-object" v-if="!file.is_folder && file.type.match('image/*')">
                                                        <i class="fa fa-fw fa-file-o fa-2x" v-if="!file.is_folder && !file.type.match('image/*')"></i>
                                                        {{file.name}}
                                                    </a>
                                                </td>
                                                <td class="hidden-xs" @click="infoFile(file, index)">{{file.updated_at | moment('from')}}</td>
                                                <td class="hidden-xs" @click="infoFile(file, index)">
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
                                <a href="#" class="heading" @click.prevent="infoFile(file, index)">
                                    <i class="fa fa-fw fa-folder-o" v-if="file.is_folder"></i>
                                    <img :src="file.urlThumbnail" class="img-responsive" v-if="!file.is_folder && file.type.match('image/*')">
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
                <!-- Info File -->
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="file-info" v-if="fileCurrent.id">
                            <div class="file-info-heading text-center">
                                <i class="fa fa-folder-o" v-if="fileCurrent.is_folder"></i>
                                <img :src="fileCurrent.urlThumbnail" v-if="!fileCurrent.is_folder && fileCurrent.type.match('image/*')">
                                <i class="fa fa-file-o" v-if="!fileCurrent.is_folder && !fileCurrent.type.match('image/*')"></i>
                            </div>
                            <hr>
                            <div class="file-info-body">
                                <p class="lead" v-if="!fileRename">
                                    {{fileCurrent.name}}
                                    <a href="#" class="btn btn-link btn-sm" @click.prevent="fileRename = true"
                                       v-if="user.hasPermission['update-files']">
                                        <i class="fa fa-fw fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </p>
                                <div class="form-group" v-else :class="{'has-error': error.name}">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" required
                                               v-model="fileCurrent.name">
                                        <span class="input-group-btn">
                                            <button class="btn btn-link" @click="updateFileName">Save</button>
                                        </span>
                                    </div>
                                    <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                </div>
                                <dl class="dl-horizontal">
                                    <dt><i class="fa fa-fw fa-database" aria-hidden="true"></i> Size</dt>
                                    <dd>
                                        <span v-if="fileCurrent.size">{{fileCurrent.size | prettyBytes}}</span>
                                        <span v-else>---</span>
                                    </dd>
                                    <dt><i class="fa fa-fw fa-user" aria-hidden="true"></i> Owner</dt>
                                    <dd>{{fileCurrent.creator.name}}</dd>
                                    <dt><i class="fa fa-fw fa-calendar-check-o" aria-hidden="true"></i> Created</dt>
                                    <dd>{{fileCurrent.created_at | moment('from')}}</dd>
                                    <dt><i class="fa fa-fw fa-calendar" aria-hidden="true"></i> Modified</dt>
                                    <dd>{{fileCurrent.updated_at | moment('from')}}</dd>
                                    <dt v-if="fileCurrent.is_folder"><i class="fa fa-fw fa-folder-open" aria-hidden="true"></i> Open Folder</dt>
                                    <dd v-if="fileCurrent.is_folder"><a :href="'/files/folder/' + fileCurrent.id">Click here</a></dd>
                                    <dt v-if="!fileCurrent.is_folder"><i class="fa fa-fw fa-download" aria-hidden="true"></i> Download File</dt>
                                    <dd v-if="!fileCurrent.is_folder"><a :href="fileCurrent.url" download>Click here</a></dd>
                                    <hr>
                                    <dt><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Shared</dt>
                                    <dd>Coming soon...</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="init-message" v-else>
                            <i class="mdi mdi-select-all" aria-hidden="true"></i>
                            <p class="lead">Nothing is selected.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Upload Files -->
        <div class="modal fade" id="modalUploadFiles">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Upload Files</h4>
                    </div>
                    <div class="modal-body">
                        <div class="media vue-clip-queue" v-for="file in filesUpload">
                            <div class="media-left">
                                <img class="media-object" :src="file.dataUrl" v-if="file.type.match('image/*')">
                                <i class="fa fa-fw fa-file-o fa-3x" v-else></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">{{file.name}}</h4>
                                <small class="text-muted" :class="{'text-danger': file.errorMessage.file}">{{file.status}}</small>
                                <small class="text-danger" v-if="file.errorMessage.file"> - {{file.errorMessage.file[0]}}</small>
                                <div class="progress" v-if="file.status">
                                    <div class="progress-bar"
                                        :class="{'progress-bar-danger': file.status == 'error'}"
                                        :style="{width: file.progress+'%'}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="filesUpload=[]">Done</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal New Folder -->
        <div class="modal fade" id="modalNewFolder">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">New Folder</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control" placeholder="Folder name" v-model="folderNew.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success"
                                v-if="user.hasPermission['create-files']"
                                @click="storeFolder">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert'
import VueClip from 'vue-clip'

export default {
    data() {
        return {
            user: Laravel.user,
            error: {},
            search: '',
            layout: 'list',
            uploadFileOptions: {
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                url: '/api/files/store',
                paramName: 'file',
                maxFilesize: {
                    limit: 10,
                    message: '{{filesize}} is greater than the {{maxFilesize}}MB'
                },
                acceptedFiles: {
                    extensions: ['image/*', 'application/pdf'],
                    message: 'You are uploading an invalid file'
                },
            },
            files: [],
            filesUpload: [],

            fileCurrent: {},
            fileRename: false,

            folderCurrentId: 0,
            folderNew: {},
        }
    },
    mounted() {
        var str = window.location.pathname;
        var res = str.split("/");

        if (res[2] == 'folder') {
            this.folderCurrentId = parseInt(res[3]);
            axios.get('/api/files/byCreator/' + this.folderCurrentId)
            .then(response => {
                this.files = response.data;
            });
        } else {
            axios.get('/api/files/byCreator')
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
        uploadFileSending (file, xhr, formData) {
            this.filesUpload.push(file);
            formData.append('parent_id', this.folderCurrentId);
            $('#modalUploadFiles').modal('show');
        },
        uploadFileComplete (file, status, xhr) {
            if (status == 'success')
                this.files.push(JSON.parse(xhr.response));
        },
        newFolder () {
            this.folderNew = {
                parent_id: this.folderCurrentId,
                is_folder: true,
            };
            $('#modalNewFolder').modal('show');
        },
        storeFolder (e) {
            axios.post('/api/files/store', this.folderNew)
            .then(response => {
                this.files.unshift(response.data);
                this.folderNew = {};
                this.error = {};
                $('#modalNewFolder').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyFile () {
            var self = this;
            var whatIs = 'file';

            if (self.fileCurrent.is_folder)
                whatIs = 'folder'

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this "+whatIs+"!",
                icon: "warning",
                buttons: true
            })
            .then((value) => {
                if (value) {
                    axios.delete('/api/files/destroy/' + self.fileCurrent.id)
                    .then(response => {
                        self.files.splice(self.fileCurrent.index, 1);
                        self.fileCurrent = {};
                        swal({
                            title: "Deleted!",
                            text: "Your "+whatIs+" has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        });
                        this.error = {};
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                }
            });
        },
        openFile (file) {
            if (file.is_folder) {
                location.href = '/files/folder/' + file.id;
            } else {
                location.href = file.url;
            }
        },
        infoFile (file, index) {
            this.fileCurrent = file;
            this.fileCurrent.index = index;
        },
        updateFileName () {
            axios.put('/api/files/update/'+this.fileCurrent.id, {name: this.fileCurrent.name})
            .then(response => {
                this.files[this.fileCurrent.index].name = response.data.name;
                this.fileRename = false;
            })
            .catch(error => {
                this.error = error.response.data;
            });
        },
        toggleLayout () {
            this.layout == 'list' ? this.layout = 'grid' : this.layout = 'list';
            localStorage.setItem('files', JSON.stringify({'layout':this.layout}));
        },
        comingSoon () {
            swal('Coming soon...');
        },
    }
}
</script>
