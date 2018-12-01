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

                            <td>{{ props.item.name }}</td>
                            <td>{{ props.item.email }}</td>
                            <td>{{ props.item.scope }}</td>
                            <td>{{ props.item.email_verified_at }}</td>
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

                <v-text-field
                    v-model="record.email"
                    label="Email"
                ></v-text-field>

                <v-select
                    v-model="record.scope"
                    :items="roles"
                    label="Scope"
                ></v-select>
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
    name: 'page-users',

    mixins: [CRUD],

    data:() => ({
        roles: []
    }),

    created() {
        this.page.icon = 'supervisor_account';
        this.page.title = 'User Management';
        this.page.subtitle = 'some describtion display here';

        this.form.title = 'User'

        this.breadcrumbs = [
            { text: 'Home', href: '/backend/' },
            { text: 'User Management', disabled: true }
        ];

        this.headers = [
            { text: 'Name', value: 'name' },
            { text: 'Email', value: 'email' },
            { text: 'Scope', value: 'scope' },
            { text: 'Verified', value: 'email_verified_at', class: 'date-updated' },
        ];

        this.dataUrl = '/api/users';

        this.fetchCombo('/api/roles', items => this.roles = items);
    },

    methods: {
        newRecord: function() {
            this.record = {
                id: null,
                name: null,
                email: null,
                scope: null
            };
        },

        openLink: function() {
            // this.$router.push({ name: 'OtherPage', params: { param: this.record.id }});
        }
    }
};
</script>