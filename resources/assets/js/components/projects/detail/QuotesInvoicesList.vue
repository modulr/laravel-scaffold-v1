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
                            <th>Amount</th>
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
                            <td>{{a.pivot.amount | currency}}</td>
                            <td>
                                <a href="#" 
                                    v-if="user.hasPermission['delete-invoices']"
                                    @click.prevent="deleteInvoice(a, index)">
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
            user: Laravel.user,
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
        deleteInvoice (invoice, index) {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Invoice!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/invoices/destroy/' + invoice.id)
                .then(response => {
                    self.$parent.invoices.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The Invoice has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                    self.error = {};
                    $('#myModalInvoices').modal('hide')
                })
                .catch(error => {
                    self.$parent.invoices.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The Invoice has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                    $('#myModalInvoices').modal('hide')
                    // self.error = error.response.data
                });
            });
        }
    }
}
</script>
