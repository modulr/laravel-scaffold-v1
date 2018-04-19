<template lang="html">
    <div class="modal-content autoparts-qr">
        <!-- Modal header -->
        <div class="modal-header">
            <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <qrcode-reader @decode="findAutopart" v-if="show">
                <div class="qr-scanner-back"></div>
                <div class="qr-scanner"><em></em><span></span></div>
                <p class="qr-error text-danger" v-html="error"></p>
            </qrcode-reader>
        </div>
    </div>
</template>

<script>
import { QrcodeReader } from 'vue-qrcode-reader'

export default {
    data () {
        return {
            error: '',
            show: false
        }
    },
    components: {
        QrcodeReader
    },
    mounted () {
        $('#modalSearchQR').on('show.bs.modal', this.showScanQR)
        $('#modalSearchQR').on('hidden.bs.modal', this.hideScanQR)
    },
    methods: {
        showScanQR () {
            this.error = ''
            this.show = true
        },
        hideScanQR () {
            this.error = ''
            this.show = false
        },
        findAutopart (id) {
            axios.get(`/api/autoparts/show/${id}`)
            .then(response => {
                if (response.data) {
                    var autopart = response.data
                    this.$emit('find', autopart)

                    $('#modalSearchQR').modal('hide')
                } else {
                    this.error = `The Autopart <strong>${id}</strong> do not exist!`
                }
            });
        }
    }
}
</script>
