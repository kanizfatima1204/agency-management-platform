<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ tasks: Object });
const user = computed(() => usePage().props.auth.user);
const columns = [
  { key: 'todo', label: 'To do', color: 'lilac' },
  { key: 'in_progress', label: 'In progress', color: 'blue' },
  { key: 'review', label: 'In review', color: 'amber' },
  { key: 'done', label: 'Done', color: 'green' },
];
const grouped = computed(() => Object.fromEntries(columns.map(column => [column.key, props.tasks.data.filter(task => task.status === column.key)])));
const moveTask = (task, status) => router.put(`/tasks/${task.id}`, { title: task.title, status, priority: task.priority, due_date: task.due_date }, { preserveScroll: true });
const canUpdate = task => user.value?.role === 'admin' || task.assignee_id === user.value?.id;
</script>

<template>
  <AppLayout>
    <template #title>{{ user?.role === 'client' ? 'Project milestones' : user?.role === 'intern' ? 'Learning tasks' : user?.role === 'admin' ? 'Task board' : 'My task board' }}</template>
    <div class="board-intro"><div><span class="eyebrow">DELIVERY IN MOTION</span><h2>{{ user?.role === 'client' ? 'See every milestone.' : user?.role === 'intern' ? 'A clear path through your work.' : 'Keep the next step moving.' }}</h2><p>{{ user?.role === 'client' ? 'Track milestone status across your projects.' : user?.role === 'intern' ? 'Your assigned tasks, grouped by progress.' : 'A live view of work moving across your projects.' }}</p></div><span class="board-total">{{ tasks.total }} <small>tasks</small></span></div>
    <div class="kanban-board">
      <section v-for="column in columns" :key="column.key" class="kanban-column" :class="`column-${column.color}`">
        <div class="kanban-heading"><span class="status-dot"></span><b>{{ column.label }}</b><span class="count-pill">{{ grouped[column.key].length }}</span></div>
        <article v-for="task in grouped[column.key]" :key="task.id" class="task-card">
          <div class="task-card-meta"><span>{{ task.project?.name }}</span><span class="priority-dot" :class="`priority-${task.priority}`" :title="task.priority"></span></div>
          <Link :href="`/projects/${task.project_id}`" class="task-card-title">{{ task.title }}</Link>
          <div class="task-card-footer"><span class="assignee-avatar">{{ task.assignee?.name?.charAt(0) || '?' }}</span><span>{{ task.assignee?.name || 'Unassigned' }}</span><select v-if="canUpdate(task)" :value="task.status" aria-label="Update task status" @change="moveTask(task, $event.target.value)"><option value="todo">To do</option><option value="in_progress">In progress</option><option value="review">Review</option><option value="done">Done</option></select><small v-else>{{ task.due_date || '—' }}</small></div>
        </article>
        <div v-if="!grouped[column.key].length" class="kanban-empty">Nothing here yet</div>
      </section>
    </div>
    <div v-if="tasks.last_page > 1" class="pagination-note">Showing the latest 30 tasks. Narrow your view by opening a project.</div>
  </AppLayout>
</template>
