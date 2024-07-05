<template>
    <div class="d-flex gap-3 justify-content-end align-items-center mb-3">
        <div>
            <label for="date-from" class="form-label">Дата от</label>
            <input v-model="sortDates.dateFrom" type="date" class="form-control" id="date-from">
        </div>
        <div>
            <label for="date-to" class="form-label">Дата до</label>
            <input v-model="sortDates.dateTo" type="date" class="form-control" id="date-to">
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-primary" @click="applySort()" :disabled="isSortClicked">
                Сортировать
            </button>
            <button v-if="isSortApplied" class="btn btn-danger" @click="applySort(true)" :disabled="isSortClicked">
                Сбросить
            </button>
        </div>
    </div>

    <section class="row h-100" :class="isSortClicked ? 'transparent' : null">
        <div v-for="(statusValue, statusName) in statuses" class="col card m-3 p-2 pb-0">
            <div class="card-header d-flex justify-content-between pl-1 pr-0 pb-3 border-0">
                <p class="mb-0"><strong>{{ statusValue }}</strong></p>
                <button
                    type="button"
                    class="btn btn-link text-reset m-0 py-0 px-2"
                    data-ripple-color="dark"
                >
                    <i class="fas fa-ellipsis-h"></i>
                </button>
            </div>

            <div class="mt-2">
                <div
                    v-for="task in tasks[statusName]"
                    class="task card-body rounded bg-white shadow-2 mb-2"
                    @click="openTaskModal(statusName, task)"
                >
                    <h6 class="fw-bold">{{ task.title }}</h6>
                    <p class="mb-2">{{ task.description }}</p>
                    <small>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                            <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                            <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                        </svg>
                        {{ task.created_at_formatted }}
                    </small>
                </div>
            </div>

            <div class="border-0 pt-0 mb-2">
                <div class="d-grid mx-auto">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-ripple-color="dark"
                        @click="openTaskModal(statusName)"
                    >
                        + Добавить
                    </button>
                </div>
            </div>
        </div>
    </section>

    <Teleport to="#modals">
        <TaskModal
            v-if="showTaskModal"
            :task-data="openedTask"
            :current-status="currentStatus"
            :statuses="statuses"
            @close-modal="closeTaskModal"
            @save-task="saveTask"
            @delete-task="deleteTask"
        />
    </Teleport>

    <Toast v-if="showToast" :message="toastMessage" :color="toastColor" />
</template>

<script setup>
import { reactive, ref } from 'vue';
import TaskModal from './modals/TaskModal.vue';
import Toast from '../shared/Toast.vue';

const props = defineProps({
    tasksData: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Object,
        required: true,
    },
});

const tasks = ref(props.tasksData);
const openedTask = ref();
const currentStatus = ref();
const sortDates = reactive({
    dateFrom: null,
    dateTo: null,
});
const showTaskModal = ref(false);
const toastColor = ref();
const toastMessage = ref();
const showToast = ref(false);
const isSortApplied = ref(false);
const isSortClicked = ref(false);

function applySort(reset = false) {
    if (!(sortDates.dateFrom || sortDates.dateTo)) {
        alert('Выберите дату!');
    } else {
        isSortApplied.value = true;
        isSortClicked.value = true;

        if (reset) {
            sortDates.dateTo = null;
            sortDates.dateFrom = null;
            isSortApplied.value = false;
        }

        axios.post('/api/v1/tasks/get-tasks', sortDates)
            .then((response) => {
                const responseData = response.data;
                if (responseData.success) {
                    tasks.value = responseData.data;
                }
            })
            .catch((error) => {
                alert('Что-то пошло не так... Попробуйте снова.');
                console.log(error);
            })
            .finally(() => {
                isSortClicked.value = false;
            });
    }
}

function openTaskModal(status, task = null) {
    openedTask.value = task;
    currentStatus.value = status;
    showTaskModal.value = true;
}

function closeTaskModal() {
    openedTask.value = null;
    currentStatus.value = null;
    showTaskModal.value = false;
}

function saveTask(response) {
    const task = response.data;
    const message = response.message;

    if (openedTask.value) {
        let indexOfTaskToUpdate;

        const taskToUpdate = tasks.value[currentStatus.value].find((task) => task.id === openedTask.value.id);
        if (taskToUpdate) {
            indexOfTaskToUpdate = tasks.value[currentStatus.value].indexOf(taskToUpdate);
        }

        if (currentStatus.value !== task.status) {
            if (taskToUpdate) {
                tasks.value[currentStatus.value].splice(indexOfTaskToUpdate);
            }

            if (!tasks.value[task.status]) {
                tasks.value[task.status] = [];
            }
            tasks.value[task.status].push(task);
        } else {
            tasks.value[currentStatus.value][indexOfTaskToUpdate] = task;
        }
    } else {
        if (!tasks.value[task.status]) {
            tasks.value[task.status] = [];
        }
        tasks.value[task.status].push(task);
    }

    showTaskModal.value = false;
    displayToast(message, 'success');
}

function deleteTask(response) {
    const message = response.message;
    const taskToDelete = tasks.value[currentStatus.value].find((task) => task.id === openedTask.value.id);

    if (taskToDelete) {
        const indexOfTaskToUpdate = tasks.value[currentStatus.value].indexOf(taskToDelete);
        openedTask.value = null;
        tasks.value[currentStatus.value].splice(indexOfTaskToUpdate);
    }

    showTaskModal.value = false;
    displayToast(message, 'danger');
}

function displayToast(message, color, timeoutMs = 5000) {
    toastMessage.value = message;
    toastColor.value = color;
    showToast.value = true;

    setTimeout(() => {
        showToast.value = false;
        toastMessage.value = null;
    }, timeoutMs);
}
</script>
