<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({ stats: Object, projects: Array });
</script>

<template>
  <AppLayout>
    <template #title>Agency overview</template>
    <div class="welcome-banner admin-banner">
      <div><span class="eyebrow">YOUR AGENCY AT A GLANCE</span><h2>Good work is taking shape.</h2><p>See what's moving, who is working on it and what needs your attention.</p></div>
      <Link class="btn" href="/projects">Explore projects <span aria-hidden="true">→</span></Link>
      <div class="banner-orb orb-one"></div><div class="banner-orb orb-two"></div>
    </div>
    <div class="stats admin-stats">
      <article><div class="stat-top"><small>CLIENTS</small><span class="stat-icon">♧</span></div><b>{{ stats.clients }}</b><small class="stat-foot">Across your agency</small></article>
      <article><div class="stat-top"><small>TEAM MEMBERS</small><span class="stat-icon">♙</span></div><b>{{ stats.team }}</b><small class="stat-foot">Ready to create</small></article>
      <article><div class="stat-top"><small>PROJECTS</small><span class="stat-icon">▤</span></div><b>{{ stats.projects }}</b><small class="stat-foot">In your workspace</small></article>
      <article class="dark"><div class="stat-top"><small>COLLECTED</small><span class="stat-icon">↗</span></div><b>${{ Number(stats.revenue).toLocaleString() }}</b><small class="stat-foot">{{ stats.pending }} tasks still in motion</small></article>
    </div>
    <div id="projects" class="panel project-pulse"><div class="panel-heading"><div><small>THE BIG PICTURE</small><h2>Project pulse</h2></div><Link href="/projects" class="text-link">View all projects <span>→</span></Link></div>
      <Link v-for="project in projects" :key="project.id" :href="`/projects/${project.id}`" class="row project-row"><i class="project-icon">{{ project.name[0] }}</i><span><b>{{ project.name }}</b><small>{{ project.client?.name }} · {{ project.priority }} priority</small></span><span class="progress-wrap"><span class="progress-track"><i :style="{ width: `${project.progress}%` }"></i></span><small>{{ project.progress }}%</small></span><mark>{{ project.status.replace('_', ' ') }}</mark></Link>
      <div v-if="!projects.length" class="empty-state"><span>✦</span><b>Your next project starts here.</b><p>Create a project to bring your team and client into the same workspace.</p><Link href="/projects" class="btn">Go to projects</Link></div>
    </div>
  </AppLayout>
</template>
