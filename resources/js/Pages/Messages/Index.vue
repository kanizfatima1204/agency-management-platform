<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({ messages: Array, projects: Array });
const form = useForm({ project_id: '', body: '' });
const send = () => form.post(`/projects/${form.project_id}/messages`, { preserveScroll: true, onSuccess: () => form.reset('body') });
</script>

<template>
  <AppLayout>
    <template #title>Project messages</template>
    <div class="message-layout">
      <div class="message-feed panel"><div class="panel-heading"><div><small>RECENT CONVERSATIONS</small><h2>Across your workspace</h2></div><span class="count-pill">{{ messages.length }} updates</span></div>
        <article v-for="message in messages" :key="message.id" class="message-card"><div class="message-avatar">{{ message.user?.name?.charAt(0) }}</div><div class="message-content"><div class="message-byline"><b>{{ message.user?.name }}</b><span>·</span><Link :href="`/projects/${message.project_id}`">{{ message.project?.name }}</Link><time>{{ new Date(message.created_at).toLocaleString() }}</time></div><p>{{ message.body }}</p></div></article>
        <div v-if="!messages.length" class="empty-state"><span>✉</span><b>Start a project conversation.</b><p>Messages with your clients and team will appear here.</p></div>
      </div>
      <div class="panel compose-panel"><span class="compose-icon">✎</span><small>QUICK UPDATE</small><h2>Start a conversation</h2><p>Send a note to everyone working on a project.</p><form @submit.prevent="send"><label>Project<select v-model="form.project_id" required><option value="">Choose a project</option><option v-for="project in projects" :key="project.id" :value="project.id">{{ project.name }}</option></select></label><label>Your message<textarea v-model="form.body" rows="5" maxlength="5000" placeholder="Share an update, ask a question…" required></textarea></label><small v-if="form.errors.body" class="error">{{ form.errors.body }}</small><button class="btn" :disabled="form.processing || !projects.length">Send message <span>→</span></button></form></div>
    </div>
  </AppLayout>
</template>
