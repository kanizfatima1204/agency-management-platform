<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({ projects: Array, stats: Object });
</script>

<template>
  <AppLayout>
    <template #title>Client studio</template>
    <div class="welcome-banner client-banner">
      <div><span class="eyebrow">YOUR AGENCY WORKSPACE</span><h2>Your ideas, moving forward.</h2><p>Follow delivery, share feedback and find every project update in one place.</p></div>
      <div class="welcome-mark">✦</div>
    </div>
    <div class="stats">
      <article><small>YOUR PROJECTS</small><b>{{ stats.projects }}</b></article>
      <article><small>IN PROGRESS</small><b>{{ stats.active }}</b></article>
      <article class="dark"><small>DELIVERED</small><b>{{ stats.completed }}</b></article>
    </div>
    <div id="projects" class="panel"><div class="panel-heading"><div><small>PROJECT PORTFOLIO</small><h2>Work in progress</h2></div><span class="count-pill">{{ projects.length }} projects</span></div>
      <Link v-for="project in projects" :key="project.id" :href="`/projects/${project.id}`" class="client-project"><div class="client-project-top"><span class="project-icon">{{ project.name[0] }}</span><span class="client-project-title"><b>{{ project.name }}</b><small>{{ project.tasks_count }} tasks · Due {{ project.due_date || 'not scheduled' }}</small></span><mark>{{ project.status.replace('_', ' ') }}</mark></div><div class="progress-track"><i :style="{ width: `${project.progress}%` }"></i></div><div class="progress-meta"><span>Delivery progress</span><b>{{ project.progress }}%</b></div></Link>
      <div v-if="!projects.length" class="empty-state"><span>✦</span><b>Your next big idea starts here.</b><p>Projects from your agency will show up here.</p></div>
    </div>
  </AppLayout>
</template>
