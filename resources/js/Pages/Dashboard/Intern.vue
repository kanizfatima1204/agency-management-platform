<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { computed } from 'vue';

const props = defineProps({ projects: Array, tasks: Array, stats: Object });
const nextTask = computed(() => props.tasks.find(task => task.status !== 'done'));
const completion = computed(() => props.tasks.length ? Math.round(props.stats.completed / props.tasks.length * 100) : 0);
</script>

<template>
  <AppLayout>
    <template #title>Intern learning hub</template>
    <div class="welcome-banner intern-banner">
      <div><span class="eyebrow">YOUR GROWTH SPACE</span><h2>Learn by making things happen.</h2><p>See your assignments, follow project progress and keep your next step clear.</p></div>
      <div class="welcome-mark">✳</div>
    </div>
    <div class="stats">
      <article><small>PROJECTS YOU'RE LEARNING ON</small><b>{{ stats.projects }}</b></article>
      <article><small>IN PROGRESS</small><b>{{ stats.open }}</b></article>
      <article class="dark"><small>COMPLETED</small><b>{{ stats.completed }}</b></article>
    </div>
    <div class="intern-journey">
      <div class="learning-card"><div class="learning-card-copy"><span class="eyebrow">YOUR LEARNING JOURNEY</span><h2>Small steps add up.</h2><p>Every task you finish builds real project experience.</p><div class="learning-steps"><span class="step-done">✓</span><i></i><span :class="{ 'step-current': stats.open > 0, 'step-done': stats.open === 0 }">{{ stats.open === 0 ? '✓' : '2' }}</span><i></i><span>3</span><label>Getting started</label><label>Hands-on work</label><label>Deliver together</label></div></div><div class="learning-ring" :style="{ '--completion': `${completion}%` }"><div><b>{{ completion }}%</b><small>tasks done</small></div></div></div>
      <div class="next-task-card"><span class="eyebrow">{{ nextTask ? 'YOUR NEXT STEP' : 'NICE WORK' }}</span><div v-if="nextTask"><h3>{{ nextTask.title }}</h3><p>{{ nextTask.project?.name }} · {{ nextTask.status.replace('_', ' ') }}</p><Link :href="`/projects/${nextTask.project_id}`" class="text-link">Open assignment <span>→</span></Link></div><div v-else><h3>You’re all caught up.</h3><p>Your next assignment will appear here.</p></div><span class="next-task-spark">✳</span></div>
    </div>
    <div class="dashboard-grid intern-lower-grid">
      <div id="tasks" class="panel"><div class="panel-heading"><div><small>ASSIGNMENT CHECKLIST</small><h2>Your tasks</h2></div><span class="count-pill">{{ tasks.length }} total</span></div>
        <div v-if="tasks.length" v-for="task in tasks" :key="task.id" class="row task-row"><i class="task-dot">{{ task.status === 'done' ? '✓' : '•' }}</i><span><b>{{ task.title }}</b><small>{{ task.project?.name }} · {{ task.status.replace('_', ' ') }}</small></span><mark>{{ task.priority }}</mark></div>
        <div v-else class="empty-state"><span>✦</span><b>Ready for your first assignment?</b><p>When a project lead assigns a task, it will show up here.</p></div>
      </div>
      <div class="panel"><div class="panel-heading"><div><small>YOUR TEAM</small><h2>Projects you’re part of</h2></div></div>
        <Link v-for="project in projects" :key="project.id" :href="`/projects/${project.id}`" class="project-mini"><span class="project-icon">{{ project.name[0] }}</span><span><b>{{ project.name }}</b><small>{{ project.client?.name }} · {{ project.status }}</small></span><span class="arrow">↗</span></Link>
        <div v-if="!projects.length" class="empty-state"><p>Your project assignments will appear here.</p></div>
      </div>
    </div>
  </AppLayout>
</template>
