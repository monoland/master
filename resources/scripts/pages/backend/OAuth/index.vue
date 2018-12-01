<template>
    <v-container class="pa-0" fluid fill-height>
        <v-layout column>
            <v-flex md12>
                <m-header 
                    :icon="page.icon"
                    :title="page.title"
                    :subtitle="page.subtitle"
                >
                    <m-button icon="add" :disabled="disabled.add" @click="openAddnew"></m-button>
                    <m-button icon="folder" :disabled="disabled.link" @click=""></m-button>
                    <m-button icon="edit" :disabled="disabled.edit" @click="openUpdate"></m-button>
                    <m-button icon="delete" :disabled="disabled.delete" @click="openDelete"></m-button>
                    <m-button icon="refresh" @click="refresh"></m-button>
                    <m-button icon="search" :disabled="disabled.find" @click="openFind"></m-button>
                </m-header>

                <m-search v-model="searchText" @close="closeFinder" v-show="form.onFind" ref="elmInput"></m-search>

                <v-breadcrumbs :items="breadcrumbs" dark></v-breadcrumbs>

                <v-data-table v-model="selected"
                    :headers="headers"
                    :items="records"
                    :pagination.sync="tablePaging"
                    :total-items="table.totalRecords"
                    :rows-per-page-items="table.page"
                    select-all
                >
                    <!-- table fill -->
                    <template slot="items" slot-scope="props">
                        <tr :active="props.selected" @click="props.selected = !props.selected">
                            <td>
                                <v-checkbox
                                    :input-value="props.selected"
                                    primary hide-details
                                ></v-checkbox>
                            </td>
                            <td>{{ props.item.id }}</td>
                            <td>{{ props.item.name }}</td>
                            <td>{{ props.item.type }}</td>
                            <td>{{ props.item.revoked }}</td>
                        </tr>
                    </template>
                    <!-- end fill -->

                    <template slot="no-data">
                        <div class="v-datatable__message">
                            <v-icon>sms_failed</v-icon>
                            <div class="v-datatable__text">{{ table.emptyMessage }}</div>
                        </div>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>

        <m-form :title="form.title"
            v-model="form.onShow"
            @close="closeForm"
        >
            <!-- form fill -->
            <v-form>
                <v-text-field
                    v-model="record.name"
                    label="Name"
                ></v-text-field>

                <label class="subheader text-uppercase">Grand Type</label>
                <v-radio-group v-model="record.type">
                    <v-radio label="Password Grand Token" :value="true"></v-radio>
                    <v-radio label="Client Grand Token" :value="false"></v-radio>
                </v-radio-group>

                <template v-if="form.onEdit">
                    <v-text-field
                        v-model="record.id"
                        label="Id"
                    ></v-text-field>

                    <v-textarea
                        v-model="record.secret"
                        label="Secret"
                    ></v-textarea>
                </template>
            </v-form>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-grey" @click="submitForm" flat>Submit</v-btn>
            </v-card-actions>
            <!-- end fill -->
        </m-form>

        <m-delete v-model="form.onDelete" :records="selected" @cancel="closeDelete" @confirm="postDelete"></m-delete>
        <m-loader v-model="loader.onShow"></m-loader>
    </v-container>
</template>

<script>
import CRUD from '@scripts/mixins';

export default {
    name: 'page-oauth',

    mixins: [CRUD],

    created() {
        this.page.icon = 'event_seat';
        this.page.title = 'Apps Management';
        this.page.subtitle = 'some describtion display here';

        this.form.title = 'Application'

        this.breadcrumbs = [
            { text: 'Home', href: '/backend/' },
            { text: 'Apps Management', disabled: true }
        ];

        this.headers = [
            { text: 'ID', value: 'id' },
            { text: 'Name', value: 'name' },
            { text: 'Password Client', value: 'password_client', class: 'date-updated' },
            { text: 'Revoked', value: 'revoked', class: 'date-updated' }
        ];

        this.dataUrl = '/api/apps';
    },

    methods: {
        newRecord: function() {
            this.record = {
                id: null,
                secret: null,
                name: null,
                type: true,
                revoked: false
            };
        },

        openLink: function() {
            // this.$router.push({ name: 'OtherPage', params: { param: this.record.id }});
        }
    }
};
</script>