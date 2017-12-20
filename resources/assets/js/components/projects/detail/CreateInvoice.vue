<template>
  <div class="modal right fade" id="modalAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Invoice</h4>
                </div>
                <div class="modal-body">
                    {{ 1 + 1 }}
                    {{ error }}
                    <!-- <form class="form-horizontal" action="index.html" method="post">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Project</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{project.name}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Customer</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{project.client.customer.name}}</small>
                                </p>
                            </div>
                        </div>                        
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label class="col-sm-3 control-label required">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" class="form-control input-lg" placeholder="Description" required v-model="invoice.description">
                                </textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Amount </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-lg" placeholder="Amount" required v-model="invoice.amount">
                            </div>
                            <label class="col-sm-3 control-label required">Currency </label>
                            <div class="col-sm-9">
                                <select type="text" class="form-control input-lg" placeholder="Currency" required v-model="invoice.currency">
                                    <option v-for="currency in list.currencies" :value="currency.id"> {{ currency.title }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.service}">
                            <label class="col-sm-3 control-label required">Service</label>
                            <div class="col-sm-9">
                                <multiselect v-model="invoice.quotes" track-by="name" label="name" :options="quotes" placeholder="Select a Quote" :multiple="true"></multiselect>
                                <span class="help-block" v-if="error.service">{{error.service[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <dropzone id="myVueDropzone" ref="myVueDropzoneInvoice"
                                    url="/attachments/store" :use-font-awesome=true
                                    :use-custom-dropzone-options=true :dropzoneOptions="dzOptions"
                                    v-on:vdropzone-success="uploadSuccess">                                    
                                </dropzone>
                            </div>
                        </div>                        
                    </form> -->
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="store">Add</button> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Multiselect from 'vue-multiselect';
import Dropzone from 'vue2-dropzone';
export default {
    props: ['list', 'invoices', 'invoice', 'project', 'quotes'],
    components: {
        Multiselect,
        Dropzone
    },
    data() {
        return {
            error: {},
            dzOptions: {
                acceptedFileTypes: '.pdf',
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                autoProcessQueue: false
            },         
        }
    },
    methods : {        
        store (e) {
            var btn = $(e.target).button('loading')            
            this.$refs.myVueDropzoneInvoice.processQueue()            
        },
        addingParams (file, xhr, formData) {
            formData.append('invoice', this.invoice);
        },        
        uploadSuccess (file, response) {
            console.log(response)
        },
    }
}
</script