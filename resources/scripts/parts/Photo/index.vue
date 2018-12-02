<template>
    <v-avatar size="128" color="purple red--after">
        <div class="v-photo" :id="uuid">
            <v-icon dark>photo_camera</v-icon>
        </div>
        <img :src="srcPath" alt="photo" v-if="srcPath">
    </v-avatar>
</template>

<script>
import qq from 'fine-uploader/lib/core';
import shortid from 'shortid';

export default {
    name: 'm-photo',

    props: {
        acceptFiles: {
            type: String,
            default: 'image/png, image/jpeg'
        },

        allowedExtensions: {
            type: Array,
            default: () => (['png', 'jpg', 'jpeg'])
        },

        completed: {
            type: Function,
            default: function(){}
        },

        endPoint: {
            type: String,
            default: function() {
                return this.$auth.baseUrl() + '/api/media';
            } 
        },

        token: {
            type: String,
            default: function() {
                return this.$auth.token();
            }
        },

        value: String
    },

    data:() => ({
        uuid: shortid.generate(),
        uploaded: false,
        uplvalue: 0,
        srcPath: null
    }),

    created() {
        this.srcPath = this.value;
    },

    mounted() {
        let _this = this;

        new qq.FineUploaderBasic({
            button: document.getElementById(_this.uuid),

            request: {
                customHeaders: {
                    'Authorization': _this.token
                },
                endpoint: _this.endPoint,
                filenameParam: 'fileName',
                inputName: 'fileUpload',
                uuidName: 'uuid',
                totalFileSizeName: 'totalFileSize'
            },

            chunking: {
                enabled: true,
                mandatory: true,
                paramNames: {
                    chunkSize: 'chunkSize',
                    partByteOffset: 'partByteOffset',
                    partIndex: 'partIndex',
                    totalParts: 'totalParts'
                },
                success: {
                    endpoint: _this.endPoint + '?completed=true'
                }
            },

            validation: {
                acceptFiles: _this.acceptFiles,
                allowedExtensions: _this.allowedExtensions
            },

            callbacks: {
                onUploadChunk: function(id, name, data) {
                    _this.uploaded = false;
                    _this.uplvalue = (parseInt(data.partIndex) + 1) / parseInt(data.totalParts) * 100;
                    
                    if (_this.uplvalue >= 100) {
                        _this.uploaded = true;
                    }
                },

                onComplete: function(id, name, response) {
                    if (!response.success) {
                        _this.$error = response.error;
                    } else {
                        _this.$emit('input', response.fileinfo.url.small);
                    }
                }
            }
        });
    },

    watch: {
        value: function(newval, oldval) {
            if (newval) {
                this.srcPath = newval;
            }
        }
    }
};
</script>
