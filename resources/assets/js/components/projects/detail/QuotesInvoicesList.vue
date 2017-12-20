<template>
  <div class="modal fade" id="myModalFile">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Invoices List</h4>
                </div>
                <div class="modal-body">
                    <dropzone id="myVueDropzone" ref="myVueDropzone"
                        url="/attachments/store" :use-font-awesome=true
                        :use-custom-dropzone-options=true :dropzoneOptions="dzOptions"
                        v-on:vdropzone-success="uploadSuccess">
                        <input type="hidden" name="quote_id" v-model="quote.id">
                        <input type="hidden" name="type" v-model="type">
                    </dropzone>
                    <div class="col-md-12">
                      <p v-if="!quote.oc && type === 1">
                        It is necessary to upload a file to validate the authorization
                      </p>
                      <div v-if="quote.oc">
                        <p>Authorization Attachment</p>
                        <table class="table table-hover">
                          <thead>
                            <th>Id</th>
                            <th>Name</th>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>
                                <a :href="this.quote.oc.url" target="_blank">
                                    <i class="fa fa-fw fa-file-o fa-2x"></i>
                                    {{this.quote.oc.name}}
                                </a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div v-if="quote.attachment && type !== 1">
                        <p>PDF Attachments</p>
                        <table class="table table-hover">
                          <thead>
                            <th>Id</th>
                            <th>Name</th>
                            <th></th>
                          </thead>
                          <tbody>
                            <tr v-for="(a,index) in quote.attachment">
                              <td>{{ index + 1}}</td>
                              <td>
                                <a :href="a.url" target="_blank">
                                    <i class="fa fa-fw fa-file-o fa-2x"></i>
                                    {{a.name}}
                                </a>
                              </td>
                              <td>
                                <a href="#">
                                  <i class="fa fa-trash-o" aria-hidden="true" @click.prevent="deleteAttachment(quote, a, index)"></i>
                                </a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
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
    props: ['quotes', 'quote', 'type'],
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
        uploadSuccess: function (file, response) {
            if(this.type !== 1){
                if(this.quote.attachment){
                    this.$parent.quote.attachment.push(response)
                }
                else{
                    this.$set(this.$parent.quote, 'attachment', [response])
                }
            }else {
                this.$set(this.$parent.quote, 'oc', response)
                this.$parent.type = 2
                axios.put('/quote/update/'+this.quote.id, this.quote)
                    .then(response => {
                        this.$parent.quotes[this.quote.index] = response.data
                        this.error = {}
                    })
                    .catch(error => {
                        this.error = error.response.data;
                });
            }
        },
        deleteAttachment (quote, attachment, index) {
            console.log(quote)
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Attachment!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/attachments/destroy/' + attachment.id)
                .then(response => {
                    quote.attachment.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The Attachment has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                    self.error = {};
                    $('#modalEdit').modal('hide')
                })
                .catch(error => {
                    self.error = error.response.data
                });
            });
        },
    }
}
</script>
