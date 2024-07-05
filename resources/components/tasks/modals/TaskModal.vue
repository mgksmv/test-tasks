<template>
    <div class="modal" tabindex="-1">
        <div class="overlay" @click="emits('closeModal')"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Задача</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="emits('closeModal')"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Заголовок</label>
                        <input
                            v-model="task.title"
                            type="text"
                            class="form-control"
                            :class="validationErrors?.title ? 'is-invalid' : null"
                            id="title"
                            placeholder="Введите заголовок задачи"
                        >
                        <div v-if="validationErrors?.title" class="invalid-feedback">
                            <ul>
                                <li v-for="error in validationErrors.title">{{ error }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Описание</label>
                        <textarea
                            v-model="task.description"
                            class="form-control"
                            :class="validationErrors?.description ? 'is-invalid' : null"
                            id="exampleFormControlTextarea1"
                            rows="3"
                            placeholder="Введите описание задачи"
                        >{{ task.description }}</textarea>
                        <div v-if="validationErrors?.description" class="invalid-feedback">
                            <ul>
                                <li v-for="error in validationErrors.description">{{ error }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Статус</label>
                        <select v-model="task.status" class="form-select">
                            <option v-for="(value, name) in statuses" :value="name">{{ value }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        v-if="task.id"
                        type="button"
                        class="btn btn-danger"
                        @click="deleteTask"
                        :disabled="isDeleteButtonDisabled"
                    >
                        Удалить
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="saveTask"
                        :disabled="isSaveButtonDisabled"
                    >
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, ref } from 'vue';
import { copyObject } from '@/helpers.js';

const props = defineProps({
    taskData: {
        type: Object,
    },
    currentStatus: {
        type: String,
        default: 'NEW',
    },
    statuses: {
        type: Object,
        required: true,
    },
});
const emits = defineEmits(['closeModal', 'saveTask', 'deleteTask']);

const task = ref();
const validationErrors = ref();
const isDeleteButtonDisabled = ref(false);
const isSaveButtonDisabled = ref(false);

onBeforeMount(() => {
    if (props.taskData) {
        task.value = copyObject(props.taskData);
    } else {
        task.value = {
            title: '',
            description: '',
            status: props.currentStatus,
        };
    }
});

function saveTask() {
    let url;
    if (task.value.id) {
        url = `/api/v1/tasks/${task.value.id}/update`;
    } else {
        url = '/api/v1/tasks/create';
    }

    isSaveButtonDisabled.value = true;

    axios.post(url, task.value)
        .then((response) => {
            const responseData = response.data;
            if (responseData.success) {
                emits('saveTask', responseData);
            } else {
                validationErrors.value = responseData.data.errors;
            }
        })
        .catch((error) => {
            alert('Что-то пошло не так... Попробуйте снова.');
            console.log(error);
        })
        .finally(() => {
            isSaveButtonDisabled.value = false;
        });
}

function deleteTask() {
    if (confirm('Вы уверены?')) {
        isDeleteButtonDisabled.value = true;

        axios.post(`/api/v1/tasks/${task.value.id}/delete`)
            .then((response) => {
                const responseData = response.data;
                if (responseData.success) {
                    emits('deleteTask', responseData);
                }
            })
            .catch((error) => {
                alert('Что-то пошло не так... Попробуйте снова.');
                console.log(error);
            })
            .finally(() => {
                isDeleteButtonDisabled.value = false;
            });
    }
}
</script>
