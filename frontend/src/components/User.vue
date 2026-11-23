<template>
  <div>
    <Navbar />
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="items"
        sort-by="price"
        class="elevation-5 pa-4"
        :loading="isLoading"
        loading-text="Loading... Please wait"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>User</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="900px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="secondary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                  @click="formTitle = 'Add User'"
                >
                  <v-icon left>fas fa-plus</v-icon>
                  Add
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="12" sm="6" md="3">
                        <v-text-field
                          v-model="editedItem.name"
                          label="User Name"
                          prepend-icon="mdi-account-outline"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="3">
                        <v-text-field
                          v-model="editedItem.email"
                          label="Email"
                          prepend-icon="mdi-email-outline"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="3">
                        <v-text-field
                          v-model="editedItem.password"
                          label="Password"
                          prepend-icon="mdi-lock-outline"
                          type="password"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="3">
                        <v-select
                          :items="roleItems"
                          v-model="editedItem.roles"
                          label="Role"
                          prepend-icon="mdi-shield-account-outline"
                          multiple
                          chips
                        ></v-select>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="secondary" text @click="close"> Cancel </v-btn>
                  <v-btn color="secondary" text @click="save"> Save </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="550px">
              <v-card>
                <v-card-title class="text-h5"
                  >Are you sure you want to delete this user?</v-card-title
                >
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="secondary" text @click="closeDelete"
                    >Cancel</v-btn
                  >
                  <v-btn color="secondary" text @click="deleteItemConfirm"
                    >OK</v-btn
                  >
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <div class="align-center">
            <v-menu transition="slide-y-transition" offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon color="secondary" v-bind="attrs" v-on="on">
                  <v-icon>fas fa-ellipsis-vertical</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="deleteItem(item)">
                  <v-icon style="color: red" small class="mr-2"
                    >fa-solid fa-trash</v-icon
                  >
                  <v-list-item-title>Delete</v-list-item-title>
                </v-list-item>
                <v-list-item @click="editItem(item)">
                  <v-icon style="color: orange" small class="mr-2"
                    >fa-solid fa-pencil</v-icon
                  >
                  <v-list-item-title>Edit</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </div>
        </template>
      </v-data-table>
      <v-snackbar
           v-model="snackbar1"
           absolute
          top
          color="success"
          outlined
          right
          timeout= 1500
           >
            The Data Successfully Add
          </v-snackbar>
          <v-snackbar
           v-model="snackbar2"
            absolute
          top
          color="orange"
          outlined
          right
          timeout = 1500
           >
            The Data Successfully Edit
          </v-snackbar>
          <v-snackbar
           v-model="snackbar3"
            absolute
          top
          color="error"
          outlined
          right
          timeout = 1500
           >
            The Data Successfully Delete
          </v-snackbar>
    </v-card-text>
  </div>
</template>

<script>
import api from "@/services/api";
import Navbar from "../components/NavBar.vue";
export default {
  components: {
    Navbar,
  },
  data: () => ({
    tab: null,
    dialog: false,
    dialogDelete: false,
    snackbar1: false,
    snackbar2: false,
    snackbar3: false,
    formTitle: "",
    headers: [
      {
        text: "User Name",
        align: "start",
        sortable: true,
        value: "name",
      },
      { text: "Email", value: "email" },
      { text: "Actions", value: "actions", sortable: false },
    ],
    roleItems: [],
    items: [],
    isLoading: false,
    editedIndex: -1,
    editedItem: {
      name: "",
      email: "",
      password: "",
      roles: [],
    },
    defaultItem: {
      name: "",
      email: "",
      password: "",
      roles: [],
    },
  }),

  methods: {
    editItem(item) {
      this.formTitle = "Edit User";
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },
    async deleteItemConfirm() {
      try {
        await api.delete(`/user/${this.editedItem.id}`);
        this.snackbar3 = true;
        await this.fetchData();
        this.closeDelete();
      } catch (error) {
        console.error("Error deleting item:", error);
      }
    },

    close() {
      this.dialog = false;
      this.clearEditedItem();
    },
    closeDelete() {
      this.dialogDelete = false;
      this.clearEditedItem();
    },
    clearEditedItem() {
      this.editedItem = Object.assign({}, this.defaultItem);
    },

    async fetchRoles() {
      try {
        const response = await api.get("/role");
        this.roleItems = (response.data.data || []).map((role) => role.name);
      } catch (error) {
        console.error("Error fetching roles:", error);
      }
    },

    //get data
    async fetchData() {
      try {
        const response = await api.get("/user");
        this.items = response.data.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        this.isLoading = false;
      }
    },

    //post n put data
    async save() {
      try {
        const payload = Object.assign({}, this.editedItem);

        if (!Array.isArray(payload.roles)) {
          payload.roles = payload.roles ? [payload.roles] : [];
        }

        if (!payload.password) {
          delete payload.password;
        }

        if (this.formTitle === "Add User") {
          await api.post("/user", payload);
          this.snackbar1 = true;
        } else {
          await api.put(`/user/${payload.id}`, payload);
          this.snackbar2 = true;
        }

        await this.fetchData();
        this.dialog = false;
        this.clearEditedItem();
      } catch (error) {
        console.error("Error saving data:", error.response);
      }
    },
  },
  mounted() {
    this.fetchData();
    this.fetchRoles();
    this.isLoading = true;
  },
};
</script>

<style>
</style>