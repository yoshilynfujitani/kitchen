<template>
    <Button
        label="Return Reservation"
        icon="pi pi-undo"
        @click="visible = true"
        class="bg-yellow-500 text-white rounded-md px-4 py-2 hover:bg-yellow-600"
    />
    <Dialog
        v-model:visible="visible"
        modal
        header="Return Reservation"
        :style="{ width: '25rem' }"
    >
        <!-- Part 1: -->
        <div v-if="currentStep === 1">
            <div>
                <p>Is Equipment Complete?</p>
            </div>
            <div>
                <Button
                    class="px-4 py-2 ml-2 text-white bg-green-600 rounded-md text-sm"
                    label="Yes"
                    @click="nextStep"
                />
                <Button
                    class="px-4 py-2 ml-2 text-white bg-red-600 rounded-md text-sm"
                    label="No"
                    @click="skipToPart4"
                />
            </div>
        </div>

        <!-- Part 2: -->
        <div v-if="currentStep === 2">
            <div>
                <p>Is Equipment in Good Condition?</p>
            </div>
            <div>
                <Button
                    class="px-4 py-2 ml-2 text-white bg-green-600 rounded-md text-sm"
                    label="Yes"
                    @click="nextStep"
                />
                <Button
                    class="px-4 py-2 ml-2 text-white bg-red-600 rounded-md text-sm"
                    label="No"
                    @click="skipToPar5"
                />
            </div>
        </div>

        <!-- Part 3: -->
        <div v-if="currentStep === 3">
            <Button
                class="px-4 py-2 ml-2 text-white bg-green-600 rounded-md text-sm"
                label="Return Equipment Now"
                @click="returnReservation"
            />
        </div>

        <div v-if="currentStep === 4">
            <div>
                <p>Specify the missing equipment and quantity:</p>
                <div>
                    <label for="missingEquipment"
                        >Select missing equipment:</label
                    >
                    <select v-model="selectedEquipment" id="missingEquipment">
                        <option
                            v-for="equipment in availableEquipment"
                            :key="equipment.id"
                            :value="equipment.id"
                        >
                            {{ equipment.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label for="missingQuantity">Specify the quantity:</label>
                    <input v-model="missingQuantity" type="number" min="1" />
                </div>
            </div>
            <div>
                <Button
                    class="px-4 py-2 ml-2 text-white bg-green-600 rounded-md text-sm"
                    label="Submit"
                    @click="submitMissingEquipment"
                />
            </div>
        </div>

        <!-- Part 5: -->
        <div v-if="currentStep === 5">
            <p>This is Part 5 .</p>
        </div>
    </Dialog>
    <Toast />
</template>

<script>
import axios from "axios";
import Modal from "../component/Modal.vue";
import Button from "primevue/button";
import Toast from "primevue/toast";
import Dialog from "primevue/Dialog";

export default {
    props: ["idReservation"],
    data() {
        return {
            visible: false,
            currentStep: 1,
            modalContent: {
                title: "Return Reservation",
                content: "",
            },
        };
    },
    components: {
        Toast,
        Dialog,
        Modal,
        Button,
    },
    methods: {
        nextStep() {
            this.currentStep++;
        },
        skipToPart4() {
            this.currentStep = 4;
        },
        skipToPar5() {
            this.currentStep = 5;
        },
        returnReservation() {
            axios
                .post("/return-reservation", { ID: this.idReservation })
                .then(() => {
                    this.$toast.add({
                        severity: "success",
                        summary: "Success!",
                        detail: "Equipment Returned Successfully!",
                        life: 3000,
                    });
                    this.$emit("Refresh");
                });
        },
    },
};
</script>
