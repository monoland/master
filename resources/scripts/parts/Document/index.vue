<template>
    <div class="v-document">
        <div class="v-document__info" v-if="fileinfo">
            <v-icon @click="cancel">close</v-icon>
            <div class="v-document__info--extension">
                <v-img
                    :src="`/icons/file/${fileinfo.extension}.svg`"
                >
                    <v-layout
                        slot="placeholder"
                        fill-height
                        align-center
                        justify-center
                        ma-0
                    >
                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                    </v-layout>
                </v-img>
            </div>
            
            <div class="v-document__info--wrapper">
                <div class="v-document__data">
                    <span class="label">Filename</span>
                    <span class="value">: {{ fileinfo.filename }}</span>
                </div>

                <div class="v-document__data">
                    <span class="label">Bytes</span>
                    <span class="value">: {{ fileinfo.bytes }}</span>
                </div>

                <div class="v-document__data">
                    <span class="label">Mime</span>
                    <span class="value">: {{ fileinfo.mime }}</span>
                </div>

                <div class="v-document__data">
                    <span class="label">Path</span>
                    <span class="value">: {{ fileinfo.path }}</span>
                </div>
            </div>
        </div>

        <div class="v-document__upload" :id="uuid" v-else>
            Click to upload document
        </div>
    </div>
</template>

<script>
import qq from 'fine-uploader/lib/core';
import shortid from 'shortid';

export default {
    name: 'm-document',

    props: {
        acceptFiles: {
            type: String,
            default: `
                application/msword, 
                application/vnd.openxmlformats-officedocument.wordprocessingml.document, 
                application/vnd.ms-excel, 
                application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
                application/vnd.ms-powerpoint,
                application/vnd.openxmlformats-officedocument.presentationml.presentation,
                application/pdf,
                application/x-rar-compressed,
                application/zip
            `
        },

        allowedExtensions: {
            type: Array,
            default: () => ([
                'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'rar', 'zip'
            ])
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

        value: Object
    },

    data:() => ({
        uuid: shortid.generate(),
        uploaded: false,
        fileinfo: null
    }),

    created() {
        this.fileinfo = this.value;
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
                        _this.$emit('input', response.fileinfo);
                    }
                }
            }
        });
    },

    methods: {
        cancel: function() {
            this.$emit('input', null);
        }
    },

    watch: {
        value: function(newval) {
            this.fileinfo = newval;
        }
    }
};
</script>
