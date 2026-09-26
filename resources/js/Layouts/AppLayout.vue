<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const navItems = computed(() => {
  const role = user.value?.role;
  if (role === 'admin') return [
    { label: 'Agency overview', href: '/dashboard', icon: '⌂' },
    { label: 'All projects', href: '/projects', icon: '▤' },
    { label: 'Task board', href: '/tasks', icon: '✓' },
    { label: 'Messages', href: '/messages', icon: '✉' },
    { label: 'Shared files', href: '/files', icon: '⌑' },
    { label: 'Finance', href: '/payments', icon: '$' },
  ];
  if (role === 'client') return [
    { label: 'Client studio', href: '/dashboard', icon: '⌂' },
    { label: 'My projects', href: '/projects', icon: '▤' },
    { label: 'Milestones', href: '/tasks', icon: '✓' },
    { label: 'Messages', href: '/messages', icon: '✉' },
    { label: 'Shared files', href: '/files', icon: '⌑' },
    { label: 'Billing', href: '/payments', icon: '$' },
  ];
  if (role === 'intern') return [
    { label: 'Learning hub', href: '/dashboard', icon: '✳' },
    { label: 'Assignments', href: '/projects', icon: '▤' },
    { label: 'Learning tasks', href: '/tasks', icon: '✓' },
    { label: 'Team messages', href: '/messages', icon: '✉' },
    { label: 'Learning resources', href: '/files', icon: '⌑' },
  ];
  return [
    { label: 'Team workspace', href: '/dashboard', icon: '⌂' },
    { label: 'Assigned projects', href: '/projects', icon: '▤' },
    { label: 'My task board', href: '/tasks', icon: '✓' },
    { label: 'Messages', href: '/messages', icon: '✉' },
    { label: 'Project files', href: '/files', icon: '⌑' },
  ];
});
const isActive = (href) => href.includes('#')
  ? page.url === href.replace('/dashboard', '') || page.url === href
  : (href === '/dashboard' ? page.url === href : page.url.startsWith(href));
</script>

<template>
  <div class="shell">
    <aside class="sidebar">
      <Link class="logo" href="/dashboard" aria-label="AgencyOS home"><b>A</b><strong>AgencyOS</strong></Link>
      <div class="user"><div class="user-avatar">{{ user?.name?.charAt(0) }}</div><div class="user-meta"><b>{{ user?.name }}</b><small>{{ user?.role }} account</small></div><span class="online-dot" title="Signed in"></span></div>
      <div class="nav-caption">WORKSPACE</div>
      <nav aria-label="Main navigation">
        <Link v-for="item in navItems" :key="item.href" :href="item.href" :class="{ active: isActive(item.href) }">
          <span class="nav-icon">{{ item.icon }}</span><span class="nav-label">{{ item.label }}</span><span v-if="isActive(item.href)" class="nav-indicator"></span>
        </Link>
      </nav>
      <div class="sidebar-spacer"></div>
      <div class="sidebar-note"><span class="note-spark">✦</span><b>{{ user?.role === 'intern' ? 'Keep growing' : user?.role === 'client' ? 'Your projects, together' : user?.role === 'admin' ? 'Agency at a glance' : 'Make work flow' }}</b><small>{{ user?.role === 'intern' ? 'Every task is a chance to learn.' : user?.role === 'client' ? 'Your team is moving things forward.' : user?.role === 'admin' ? 'Your workspace is ready.' : 'Your next step is waiting.' }}</small></div>
      <button class="sign-out" @click="router.post('/logout')"><span>↪</span><span class="nav-label">Sign out</span></button>
    </aside>
    <main class="main">
      <header class="topbar">
        <div><small>AGENCY WORKSPACE <span class="breadcrumb-dot">/</span> <span class="role-label">{{ user?.role }}</span></small><h1><slot name="title">Dashboard</slot></h1></div>
        <div class="topbar-right"><div class="workspace-status"><i></i> Workspace active</div><div class="avatar">{{ user?.name?.charAt(0) }}</div></div>
      </header>
      <section><slot /></section>
    </main>
  </div>
</template>
