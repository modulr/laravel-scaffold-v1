<template>
  <div class="modal fade" id="myModalInvoices">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Invoices List</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <th>Id</th>
                            <th>Name</th>
                            <th></th>
                        </thead>
                        <tbody>
                        <tr v-for="(a,index) in invoices">
                            <td>{{ index + 1}}</td>
                            <td>
                                <a :href="a.url" target="_blank">
                                    <i class="fa fa-fw fa-file-o fa-2x"></i>
                                    {{a.description}}
                                </a>
                            </td>
                            <td>
                                <a href="#">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import swal from 'sweetalert';
import Dropzone from 'vue2-dropzone';
export default {
    props: ['invoices'],
    data () {
        return {
            dzOptions: {
                acceptedFileTypes: '.jpg,.jpeg,.png,.pdf',
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
            },
        }
    },
    components: {        
        Dropzone,
    },
    methods : {
        deleteAttachment (a,b,c) {
            
        }
    }
}
</script>
