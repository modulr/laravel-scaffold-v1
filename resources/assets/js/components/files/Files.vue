<template>
    <div class="files">
        <!-- Actions buttons-->
        <div class="row">
            <div class="col-sm-6">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModalFile">
                    <i class="mdi mdi-note-add mdi-lg"></i> Upload files
                </a>
                <a href="#" class="btn btn-link" data-toggle="modal" data-target="#myModalFolder">
                    <i class="mdi mdi-create-new-folder mdi-lg"></i> New folder
                </a>
            </div>
            <div class="col-sm-6 text-right">
                <div class="buttom-group">
                    <a href="#" class="btn btn-link" :class="{ 'active': layout == 'list'}"
                        @click.prevent="toggleLayout('list')">
                        <i class="fa fa-lg fa-list" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="btn btn-link" :class="{ 'active': layout == 'grid'}"
                        @click.prevent="toggleLayout('grid')">
                        <i class="fa fa-lg fa-th" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        <br>
        <!-- List -->
        <div class="row">
            <!-- Grid -->
            <div v-if="layout == 'grid'">
                <div class="col-sm-3" v-for="(file, index) in files" >
                    <div class="thumbnail">
                        <!-- Folder -->
                        <a href="#" v-if="file.type == 2" @click.prevent="goToFolder(file.id)">
                            <i class="fa fa-folder fa-5x"></i>
                        </a>
                        <!-- File -->
                        <a :href="file.url" v-else>
                            <img :src="file.url" class="img-responsive" v-if="file.is_image">
                            <i class="fa fa-file fa-5x" v-else></i>
                        </a>
                        <div class="caption">
                            <p>{{file.name}}</p>
                            <div class="text-right">
                                <div class="dropdown">
                                    <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu" >
                                        <li>
                                            <a href="#" v-if="file.type == 2" @click.prevent="goToFolder(file.id)">
                                                <i class="fa fa-fw fa-folder-open" aria-hidden="true"></i> Open
                                            </a>
                                            <a :href="file.url" download v-else>
                                                <i class="fa fa-fw fa-download" aria-hidden="true"></i> Download
                                            </a>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#" @click.prevent="comingSoon"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Share</a></li>
                                        <li>
                                            <a href="#" @click.prevent="edit(file, index)">
                                                <i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i> Rename
                                            </a>
                                        </li>
                                        <li><a href="#" @click.prevent="info(file)"><i class="fa fa-fw fa-info-circle" aria-hidden="true"></i> Info</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#" @click.prevent="comingSoon"><i class="fa fa-fw fa-arrows" aria-hidden="true"></i> Move</a></li>
                                        <li><a href="#" @click.prevent="comingSoon"><i class="fa fa-fw fa-clone" aria-hidden="true"></i> Copy</a></li>
                                        <li>
                                            <a href="#" @click.prevent="destroy(file.id, index)">
                                                <i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- List -->
            <div class="col-md-12" v-else>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Modified</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(file, index) in files">
                            <td>
                                <!-- Folder -->
                                <a href="#" v-if="file.type == 2" @click.prevent="goToFolder(file.id)">
                                    <i class="fa fa-fw fa-folder fa-2x"></i>
                                    {{file.name}}
                                </a>
                                <!-- File -->
                                <a :href="file.url" target="_blank" v-else>
                                    <img :src="file.url" class="img-responsive" v-if="file.is_image">
                                    <i class="fa fa-fw fa-file fa-2x" v-else></i>
                                    {{file.name}}
                                </a>
                            </td>
                            <td>{{file.updated_at | ago}}</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="#" v-if="file.type == 2" @click.prevent="goToFolder(file.id)">
                                                <i class="fa fa-fw fa-folder-open" aria-hidden="true"></i> Open
                                            </a>
                                            <a :href="file.url" download v-else>
                                                <i class="fa fa-fw fa-download" aria-hidden="true"></i> Download
                                            </a>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#" @click.prevent="comingSoon"><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Share</a></li>
                                        <li>
                                            <a href="#" @click.prevent="edit(file, index)">
                                                <i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i> Rename
                                            </a>
                                        </li>
                                        <li><a href="#" @click.prevent="info(file)"><i class="fa fa-fw fa-info-circle" aria-hidden="true"></i> Info</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#" @click.prevent="comingSoon"><i class="fa fa-fw fa-arrows" aria-hidden="true"></i> Move</a></li>
                                        <li><a href="#" @click.prevent="comingSoon"><i class="fa fa-fw fa-clone" aria-hidden="true"></i> Copy</a></li>
                                        <li>
                                            <a href="#" @click.prevent="destroy(file.id, index)">
                                                <i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Init Message  -->
        <div class="panel panel-default" v-if="files.length == 0">
            <div class="panel-body text-center">
                <i class="fa fa-folder fa-5x text-muted" aria-hidden="true"></i>
                <p class="lead text-muted">Upload or create your first file/folder!!</p>
            </div>
        </div>

        <!-- Modal File -->
        <div class="modal fade" id="myModalFile">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Upload Files</h4>
                    </div>
                    <div class="modal-body">
                        <dropzone id="myVueDropzone" ref="myVueDropzone"
                            url="/file/store" :use-font-awesome=true
                            :use-custom-dropzone-options=true :dropzoneOptions="dzOptions"
                            v-on:vdropzone-success="uploadSuccess">
                            <input type="hidden" name="parent_id" v-model="newFile.parent_id">
                            <input type="hidden" name="type" v-model="newFile.type">
                        </dropzone>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Folder -->
        <div class="modal fade" id="myModalFolder">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">New Folder</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Folder name" v-model="newFile.name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" @click="store">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Update -->
        <div class="modal fade" id="myModalUpdate">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Rename</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="name" v-model="editFile.name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="update">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Update -->
        <div class="modal fade" id="myModalInfo">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">
                            <i class="fa fa-folder fa-lg" v-if="file.type == 2"></i>
                            <img :src="file.url" class="img-responsive" v-if="file.type == 1 && file.is_image">
                            <i class="fa fa-file fa-lg" v-if="file.type == 1 && !file.is_image"></i>
                            {{file.name}}
                        </h4>
                    </div>
                    <div class="modal-body">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-fw fa-share-alt" aria-hidden="true"></i> Shared Coming soon...</li>
                            <li><i class="fa fa-fw fa-user" aria-hidden="true"></i> Owner {{file.user.name}}</li>
                            <li><i class="fa fa-fw fa-calendar-check-o" aria-hidden="true"></i> Created {{file.created_at | ago}}</li>
                            <li><i class="fa fa-fw fa-calendar-o" aria-hidden="true"></i> Modified {{file.updated_at | ago}}</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import moment from 'moment';
import swal from 'sweetalert';
import Dropzone from 'vue2-dropzone';

export default {
    data() {
        return {
            files: [],
            file: {
                user:{}
            },
            newFile: {
                parent_id: 0,
                type: 1,
            },
            editFile: {},
            error: {},
            layout: 'grid',
            dzOptions: {
                acceptedFileTypes: '.jpg,.jpeg,.png,.pdf',
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
            },
        }
    },
    components: {
        Dropzone
    },
    filters: {
        ago(date) {
            return moment(date).fromNow();
        }
    },
    mounted() {
        var str = window.location.pathname;
        var res = str.split("/");

        if (res.length == 3) {

            var currentFolderId = parseInt(res[2]);

            this.newFile.parent_id = currentFolderId;

            axios.get('/file/byUser/' + currentFolderId)
            .then(response => {
                this.files = response.data;
            });
        } else {
            axios.get('/file/byUser')
            .then(response => {
                this.files = response.data;
            });
        }

        if (JSON.parse(localStorage.getItem('files'))) {
            this.layout = JSON.parse(localStorage.getItem('files')).layout;
        }
    },

    methods: {
        store: function () {
            this.newFile.type = 2;
            axios.post('/file/store', this.newFile)
            .then(response => {
                this.files.unshift(response.data);
                this.newFile.name = '';
                this.newFile.type = 1;
                $('#myModalFolder').modal('hide');
            });
        },
        destroy: function (id, index) {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this file!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/file/destroy/' + id)
                .then(response => {
                    self.files.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            });
        },
        info: function (file) {
            this.file = file;
            $('#myModalInfo').modal('show');
        },
        edit: function (file, index) {
            this.editFile = _.clone(file);
            this.editFile.index = index;
            $('#myModalUpdate').modal('show');
        },
        update: function () {
            axios.put('/file/update/'+this.editFile.id, {name: this.editFile.name})
            .then(response => {
                this.files[this.editFile.index].name = response.data.name;
                this.editFile = {};
                $('#myModalUpdate').modal('hide');
            });
        },
        goToFolder: function (id) {
            location.href = '/files/' + id;
        },
        uploadSuccess: function (file, response) {
            this.files.push(response);
            $('#myModalFile').modal('hide');
        },
        toggleLayout: function (layout) {
            this.layout = layout;
            localStorage.setItem('files', JSON.stringify({'layout':layout}));
        },
        comingSoon: function () {
            swal('Coming soon...');
        }
    }
}

</script>
