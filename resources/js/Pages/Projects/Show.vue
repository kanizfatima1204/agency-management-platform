<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
const props = defineProps({ project: Object, team: Array });
const user = computed(() => usePage().props.auth.user);
const canManage = computed(() => user.value?.role === 'admin' || props.project.members.some(member => member.id === user.value?.id));
const taskForm = useForm({ title: '', assignee_id: '', status: 'todo', priority: 'medium', due_date: '' });
const messageForm = useForm({ body: '' });
const fileForm = useForm({ file: null });
const paymentForm = useForm({ amount: '', currency: 'USD', status: 'pending', method: '', reference: '', due_date: '' });
const task = () => taskForm.post(`/projects/${props.project.id}/tasks`, { onSuccess: () => taskForm.reset('title', 'assignee_id', 'due_date') });
const message = () => messageForm.post(`/projects/${props.project.id}/messages`, { onSuccess: () => messageForm.reset() });
const upload = () => fileForm.post(`/projects/${props.project.id}/files`, { forceFormData: true, onSuccess: () => fileForm.reset() });
const payment = () => paymentForm.post(`/projects/${props.project.id}/payments`, { onSuccess: () => paymentForm.reset('amount', 'method', 'reference', 'due_date') });
const updateTask = (task, status) => router.put(`/tasks/${task.id}`, { title: task.title, status, priority: task.priority, due_date: task.due_date });
</script>
<template>
  <AppLayout><template #title>{{ project.name }}</template>
    <div class="project-top"><div><mark>{{ project.status }}</mark><p>{{ project.description }}</p><small>Client: {{ project.client?.name }}</small></div><div><b>{{ project.progress }}%</b><label><i :style="{ width: project.progress + '%' }"></i></label></div></div>
    <div class="two"><div class="panel"><small>TASKS</small><h2>Delivery checklist</h2><div v-for="t in project.tasks" :key="t.id" class="row"><i>✓</i><span><b>{{ t.title }}</b><small>{{ t.assignee?.name }} · {{ t.due_date || 'No due date' }}</small></span><select v-if="user?.role === 'admin' || t.assignee_id === user?.id" :value="t.status" @change="updateTask(t, $event.target.value)"><option value="todo">To do</option><option value="in_progress">In progress</option><option value="review">Review</option><option value="done">Done</option></select><mark v-else>{{ t.status }}</mark></div><form v-if="canManage" @submit.prevent="task" class="inline"><input v-model="taskForm.title" placeholder="New task" required><select v-model="taskForm.assignee_id" required><option value="">Assignee</option><option v-for="m in team" :key="m.id" :value="m.id">{{ m.name }} · {{ m.role }}</option></select><button class="btn" :disabled="taskForm.processing">Add task</button></form></div>
      <div class="panel"><small>PAYMENTS</small><h2>Financial trail</h2><div v-for="payment in project.payments" :key="payment.id" class="row"><span><b>{{ payment.amount }} {{ payment.currency }}</b><small>{{ payment.reference || 'Payment' }}</small></span><mark>{{ payment.status }}</mark></div><form v-if="user?.role === 'admin'" @submit.prevent="payment" class="inline"><input v-model="paymentForm.amount" type="number" min="0.01" step="0.01" placeholder="Amount" required><select v-model="paymentForm.status"><option>pending</option><option>paid</option><option>overdue</option><option>refunded</option></select><input v-model="paymentForm.reference" placeholder="Reference"><button class="btn" :disabled="paymentForm.processing">Record</button></form><hr><small>TEAM</small><div v-for="member in project.members" :key="member.id" class="person">{{ member.name }} · {{ member.role }}</div></div></div>
    <div class="two"><div class="panel"><small>PROJECT FILES</small><h2>Shared files</h2><div v-for="file in project.files" :key="file.id" class="row"><span><b>{{ file.original_name }}</b><small>{{ file.user?.name }} · {{ Math.ceil(file.size / 1024) }} KB</small></span><a :href="`/projects/${project.id}/files/${file.id}`">Download</a></div><form @submit.prevent="upload" class="inline"><input type="file" @input="fileForm.file = $event.target.files[0]" required><button class="btn" :disabled="fileForm.processing">Upload</button></form><small v-if="fileForm.errors.file" class="error">{{ fileForm.errors.file }}</small></div>
      <div class="panel"><small>COMMUNICATION</small><h2>Project room</h2><div v-for="item in project.messages" :key="item.id" class="message"><b>{{ item.user?.name }}</b><p>{{ item.body }}</p></div><form @submit.prevent="message" class="inline"><input v-model="messageForm.body" placeholder="Write an update…" required maxlength="5000"><button class="btn" :disabled="messageForm.processing">Send</button></form></div></div>
  </AppLayout>
</template>
