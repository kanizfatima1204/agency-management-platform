<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({ files: Object });
const formatSize = bytes => bytes < 1024 * 1024 ? `${Math.max(1, Math.round(bytes / 1024))} KB` : `${(bytes / 1024 / 1024).toFixed(1)} MB`;
const iconFor = mime => mime?.startsWith('image/') ? '▧' : mime?.includes('pdf') ? 'PDF' : mime?.includes('zip') ? 'ZIP' : 'DOC';
</script>

<template>
  <AppLayout>
    <template #title>Shared files</template>
    <div class="welcome-banner files-banner"><div><span class="eyebrow">PROJECT LIBRARY</span><h2>Everything your team has shared.</h2><p>Files stay private to the project and are available only to its participants.</p></div><span class="welcome-mark">⌑</span></div>
    <div class="panel"><div class="panel-heading"><div><small>RECENT UPLOADS</small><h2>Project documents</h2></div><span class="count-pill">{{ files.total }} files</span></div>
      <div v-for="file in files.data" :key="file.id" class="file-row"><span class="file-type">{{ iconFor(file.mime_type) }}</span><div class="file-details"><b>{{ file.original_name }}</b><small><Link :href="`/projects/${file.project_id}`">{{ file.project?.name }}</Link><span v-if="file.project?.client?.name"> · {{ file.project.client.name }}</span></small></div><span class="file-uploader">{{ file.user?.name }}</span><span class="file-size">{{ formatSize(file.size) }}</span><a class="file-download" :href="`/projects/${file.project_id}/files/${file.id}`">Download <span>↓</span></a></div>
      <div v-if="!files.data.length" class="empty-state"><span>⌑</span><b>Your shared library is ready.</b><p>Files uploaded inside your project spaces will appear here.</p><Link href="/projects" class="btn">Browse projects</Link></div>
    </div>
    <div v-if="files.last_page > 1" class="pagination-note">Showing {{ files.from }}–{{ files.to }} of {{ files.total }} files.</div>
  </AppLayout>
</template>
