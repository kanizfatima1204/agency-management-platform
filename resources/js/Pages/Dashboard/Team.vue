<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { computed } from 'vue';

const props = defineProps({ projects: Array, tasks: Array, stats: Object });
const lanes = [
  { key: 'todo', label: 'Up next' },
  { key: 'in_progress', label: 'In progress' },
  { key: 'review', label: 'Ready for review' },
  { key: 'done', label: 'Shipped' },
];
const grouped = computed(() => Object.fromEntries(lanes.map(lane => [lane.key, props.tasks.filter(task => task.status === lane.key)])));
</script>

<template>
  <AppLayout>
    <template #title>Team workspace</template>
    <div class="welcome-banner team-banner">
      <div><span class="eyebrow">YOUR DELIVERY DESK</span><h2>Make progress, one task at a time.</h2><p>Your assigned work and the projects moving forward.</p></div>
      <Link class="btn" href="/projects">Open projects <span aria-hidden="true">→</span></Link>
    </div>
    <div class="stats">
      <article><small>ASSIGNED PROJECTS</small><b>{{ stats.projects }}</b></article>
      <article><small>OPEN TASKS</small><b>{{ stats.open }}</b></article>
      <article class="dark"><small>COMPLETED TASKS</small><b>{{ stats.completed }}</b></article>
    </div>
    <div id="tasks" class="team-board">
      <div v-for="lane in lanes" :key="lane.key" class="team-lane"><div class="team-lane-heading"><span :class="`lane-dot lane-${lane.key}`"></span><b>{{ lane.label }}</b><span>{{ grouped[lane.key].length }}</span></div>
        <Link v-for="task in grouped[lane.key].slice(0, 3)" :key="task.id" :href="`/projects/${task.project_id}`" class="team-task-card"><small>{{ task.project?.name }}</small><b>{{ task.title }}</b><span class="team-task-bottom"><i>{{ task.priority }} priority</i><span class="arrow">↗</span></span></Link>
        <div v-if="!grouped[lane.key].length" class="team-lane-empty">Nothing in this lane</div>
      </div>
    </div>
    <div class="panel assigned-strip"><div class="panel-heading"><div><small>YOUR PROJECTS</small><h2>Where the work happens</h2></div><Link href="/projects" class="text-link">All assigned projects →</Link></div>
      <Link v-for="project in projects" :key="project.id" :href="`/projects/${project.id}`" class="project-mini"><span class="project-icon">{{ project.name[0] }}</span><span><b>{{ project.name }}</b><small>{{ project.client?.name }} · {{ project.progress }}% complete</small></span><span class="arrow">↗</span></Link>
      <div v-if="!projects.length" class="empty-state"><p>No projects assigned yet.</p></div>
    </div>
  </AppLayout>
</template>
