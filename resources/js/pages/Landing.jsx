import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

const FEATURES = [
    {
        title: 'Résidences & immeubles',
        description: 'Organisez votre parc immobilier : résidences, bâtiments, étages et adresses, le tout structuré et consultable.',
        icon: (
            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                <path strokeLinecap="round" strokeLinejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
            </svg>
        ),
    },
    {
        title: 'Appartements & affectation',
        description: 'Suivez chaque appartement, sa superficie, son étage et le résident qui y vit. Affectez ou désaffectez en un clic.',
        icon: (
            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                <path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        ),
    },
    {
        title: 'Charges & reçus',
        description: 'Créez les charges, suivez leur statut (en attente, payée, en retard) et archivez le justificatif de paiement en PDF.',
        icon: (
            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                <path strokeLinecap="round" strokeLinejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 21h14a2 2 0 002-2V7l-5-5H5a2 2 0 00-2 2v15a2 2 0 002 2z" />
            </svg>
        ),
    },
    {
        title: 'Réclamations & suivi',
        description: 'Déposez une réclamation, suivez son statut en temps réel et laissez le syndic traiter chaque demande proprement.',
        icon: (
            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                <path strokeLinecap="round" strokeLinejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        ),
    },
    {
        title: 'Analyse IA des réclamations',
        description: 'Chaque réclamation est analysée par une IA qui examine l’état des charges et produit une décision traçable.',
        icon: (
            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6l2.1 2.1m0-12.8l-2.1 2.1M7.7 16.3l-2.1 2.1" />
            </svg>
        ),
    },
    {
        title: 'Espaces dédiés par rôle',
        description: 'Administrateur, syndic et résident disposent chacun d’un tableau de bord adapté à ses responsabilités.',
        icon: (
            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h10" />
            </svg>
        ),
    },
];

const ROLES = [
    {
        title: 'Administrateur',
        tagline: 'Vue globale de la plateforme',
        description: 'Gère les utilisateurs, supervise toutes les résidences et garde un œil sur les réclamations et les audits IA.',
        link: '/login',
        color: 'from-blue-600 to-blue-500',
    },
    {
        title: 'Syndic',
        tagline: 'Pilote ses résidences',
        description: 'Gère immeubles, appartements, charges et reçus, traite les réclamations et déclenche les analyses IA.',
        link: '/login',
        color: 'from-indigo-600 to-indigo-500',
    },
    {
        title: 'Résident',
        tagline: 'Son espace personnel',
        description: 'Consulte ses appartements et ses charges, télécharge ses reçus et suit ses réclamations pas à pas.',
        link: '/register',
        color: 'from-sky-500 to-cyan-400',
    },
];

const STATS = [
    { value: '3', label: 'profils d’accès' },
    { value: '100 %', label: 'réclamations tracées' },
    { value: 'IA', label: 'analyse intégrée' },
    { value: 'PDF', label: 'reçus téléchargeables' },
];

const NAV_ITEMS = [
    { href: '#fonctionnalites', label: 'Fonctionnalités' },
    { href: '#roles', label: 'Profils' },
    { href: '#ia', label: 'Intelligence artificielle' },
    { href: '#contact', label: 'Contact' },
];

function Logo() {
    return (
        <span className="inline-flex items-center gap-2.5">
            <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-base font-bold text-white">S</span>
            <span className="text-lg font-semibold text-gray-900">SyndicFlow</span>
        </span>
    );
}

function DashboardMockup() {
    return (
        <div className="relative mx-auto w-full max-w-md">
            <div className="absolute -inset-4 rounded-3xl bg-gradient-to-br from-blue-100 via-white to-indigo-100 blur-sm" aria-hidden="true" />
            <div className="relative rounded-2xl border border-gray-200 bg-white p-5 shadow-xl">
                <div className="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div className="flex items-center gap-2">
                        <span className="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">S</span>
                        <span className="text-sm font-semibold text-gray-900">Tableau de bord</span>
                    </div>
                    <div className="flex gap-1.5">
                        <span className="h-2.5 w-2.5 rounded-full bg-gray-200" />
                        <span className="h-2.5 w-2.5 rounded-full bg-gray-200" />
                        <span className="h-2.5 w-2.5 rounded-full bg-gray-200" />
                    </div>
                </div>
                <div className="mt-4 grid grid-cols-3 gap-3">
                    {[
                        { icon: '🏢', label: 'Résidences', value: '3' },
                        { icon: '📝', label: 'Réclamations', value: '12' },
                        { icon: '🤖', label: 'Audits IA', value: '9' },
                    ].map((s) => (
                        <div key={s.label} className="rounded-xl border border-gray-100 bg-gray-50 p-3">
                            <span className="text-sm">{s.icon}</span>
                            <p className="mt-1 text-lg font-bold text-gray-900">{s.value}</p>
                            <p className="text-[10px] text-gray-500">{s.label}</p>
                        </div>
                    ))}
                </div>
                <div className="mt-4 rounded-xl border border-gray-100 p-3">
                    <p className="text-[11px] font-semibold text-gray-700">Dernières réclamations</p>
                    <div className="mt-2 space-y-2">
                        <div className="flex items-center justify-between rounded-lg bg-blue-50 px-3 py-2">
                            <span className="text-[11px] font-medium text-gray-700">Fuite dans la salle de bain</span>
                            <span className="rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-semibold text-amber-700">Urgente</span>
                        </div>
                        <div className="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                            <span className="text-[11px] font-medium text-gray-700">Ascenseur en panne</span>
                            <span className="rounded-full bg-blue-100 px-2 py-0.5 text-[9px] font-semibold text-blue-700">En cours</span>
                        </div>
                        <div className="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                            <span className="text-[11px] font-medium text-gray-700">Lumière du couloir</span>
                            <span className="rounded-full bg-green-100 px-2 py-0.5 text-[9px] font-semibold text-green-700">Traitée</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

function AuditMockup() {
    return (
        <div className="relative mx-auto w-full max-w-md">
            <div className="absolute -inset-4 rounded-3xl bg-gradient-to-br from-indigo-100 via-white to-sky-100 blur-sm" aria-hidden="true" />
            <div className="relative rounded-2xl border border-gray-200 bg-white p-5 shadow-xl">
                <div className="flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span className="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-xs font-bold text-white">S</span>
                    <span className="text-sm font-semibold text-gray-900">Audit IA · Réclamation #12</span>
                    <span className="ml-auto rounded-full bg-green-100 px-2 py-0.5 text-[9px] font-semibold text-green-700">Terminé</span>
                </div>
                <div className="mt-4 space-y-3">
                    <div className="flex items-center justify-between rounded-xl border border-gray-100 px-4 py-3">
                        <div>
                            <p className="text-[11px] font-semibold text-gray-900">Décision</p>
                            <p className="text-[10px] text-gray-500">Rejetée · charge déjà réglée</p>
                        </div>
                        <span className="rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold text-red-700">Rejetée</span>
                    </div>
                    <div className="rounded-xl bg-gray-50 p-4">
                        <p className="text-[11px] font-semibold text-gray-700">Analyse</p>
                        <p className="mt-1 text-[10px] leading-relaxed text-gray-500">
                            La charge correspondante est déjà soldée : la réclamation ne peut pas être acceptée.
                        </p>
                        <div className="mt-2 flex items-center gap-1.5">
                            <span className="h-1.5 w-1.5 rounded-full bg-green-500" />
                            <span className="text-[9px] text-gray-400">modèle openai/gpt-oss-20b</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default function Landing() {
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 12);
        onScroll();
        window.addEventListener('scroll', onScroll);
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    return (
        <div className="bg-white text-gray-900 antialiased">
            <header
                className={`fixed inset-x-0 top-0 z-50 transition-all duration-300 ${
                    scrolled ? 'border-b border-gray-100 bg-white/90 shadow-sm backdrop-blur' : 'bg-transparent'
                }`}
            >
                <div className="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                    <Link to="/" aria-label="SyndicFlow — accueil">
                        <Logo />
                    </Link>
                    <nav className="hidden items-center gap-8 md:flex">
                        {NAV_ITEMS.map((item) => (
                            <a key={item.href} href={item.href} className="text-sm font-medium text-gray-600 transition hover:text-blue-600">
                                {item.label}
                            </a>
                        ))}
                    </nav>
                    <div className="flex items-center gap-3">
                        <Link to="/login" className="text-sm font-medium text-gray-700 transition hover:text-blue-600">
                            Connexion
                        </Link>
                        <Link
                            to="/register"
                            className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                        >
                            S’inscrire
                        </Link>
                    </div>
                </div>
            </header>

            <section className="relative overflow-hidden bg-gradient-to-b from-blue-50/60 via-white to-white pt-32 pb-20">
                <div
                    className="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-blue-100/60 blur-3xl"
                    aria-hidden="true"
                />
                <div
                    className="pointer-events-none absolute top-40 -left-32 h-80 w-80 rounded-full bg-indigo-100/60 blur-3xl"
                    aria-hidden="true"
                />
                <div className="relative mx-auto grid max-w-6xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2">
                    <div>
                        <span className="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                            <span className="h-1.5 w-1.5 rounded-full bg-blue-600" />
                            Gestion de copropriété nouvelle génération
                        </span>
                        <h1 className="mt-6 text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                            Pilotez votre copropriété{' '}
                            <span className="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                en toute sérénité
                            </span>
                        </h1>
                        <p className="mt-5 max-w-lg text-lg leading-relaxed text-gray-600">
                            SyndicFlow centralise résidences, immeubles, appartements, charges et réclamations — et s’appuie sur
                            l’intelligence artificielle pour analyser chaque demande.
                        </p>
                        <div className="mt-8 flex flex-wrap items-center gap-4">
                            <Link
                                to="/register"
                                className="rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700"
                            >
                                Créer un compte gratuit
                            </Link>
                            <a
                                href="#fonctionnalites"
                                className="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:border-blue-300 hover:text-blue-600"
                            >
                                Découvrir les fonctionnalités
                            </a>
                        </div>
                        <p className="mt-6 text-xs text-gray-400">Aucune carte bancaire requise · Démo disponible sur demande</p>
                    </div>
                    <DashboardMockup />
                </div>
            </section>

            <section className="border-y border-gray-100 bg-gray-50/70">
                <div className="mx-auto grid max-w-6xl grid-cols-2 gap-8 px-4 py-10 sm:px-6 md:grid-cols-4">
                    {STATS.map((s) => (
                        <div key={s.label} className="text-center">
                            <p className="text-3xl font-bold text-gray-900">{s.value}</p>
                            <p className="mt-1 text-sm text-gray-500">{s.label}</p>
                        </div>
                    ))}
                </div>
            </section>

            <section id="fonctionnalites" className="scroll-mt-20 py-20">
                <div className="mx-auto max-w-6xl px-4 sm:px-6">
                    <div className="mx-auto max-w-2xl text-center">
                        <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">Tout ce qu’il faut pour gérer votre copropriété</h2>
                        <p className="mt-4 text-lg text-gray-600">
                            Des bâtiments aux reçus de paiement, chaque détail est centralisé dans un seul outil.
                        </p>
                    </div>
                    <div className="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {FEATURES.map((f) => (
                            <div
                                key={f.title}
                                className="group rounded-2xl border border-gray-100 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-600/5"
                            >
                                <span className="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                                    {f.icon}
                                </span>
                                <h3 className="mt-4 text-base font-semibold text-gray-900">{f.title}</h3>
                                <p className="mt-2 text-sm leading-relaxed text-gray-500">{f.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <section id="roles" className="scroll-mt-20 bg-gray-50/70 py-20">
                <div className="mx-auto max-w-6xl px-4 sm:px-6">
                    <div className="mx-auto max-w-2xl text-center">
                        <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">Un espace pensé pour chaque profil</h2>
                        <p className="mt-4 text-lg text-gray-600">Chaque acteur de la copropriété dispose d’un tableau de bord dédié.</p>
                    </div>
                    <div className="mt-14 grid gap-6 md:grid-cols-3">
                        {ROLES.map((r) => (
                            <div key={r.title} className="flex flex-col rounded-2xl border border-gray-100 bg-white p-6">
                                <span className={`inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br ${r.color} text-white`}>
                                    <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <h3 className="mt-4 text-lg font-semibold text-gray-900">{r.title}</h3>
                                <p className="text-sm font-medium text-blue-600">{r.tagline}</p>
                                <p className="mt-3 flex-1 text-sm leading-relaxed text-gray-500">{r.description}</p>
                                <Link to={r.link} className="mt-5 text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                                    En savoir plus →
                                </Link>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <section id="ia" className="scroll-mt-20 py-20">
                <div className="mx-auto grid max-w-6xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2">
                    <div className="order-2 lg:order-1">
                        <AuditMockup />
                    </div>
                    <div className="order-1 lg:order-2">
                        <span className="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                            <svg className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Intelligence artificielle
                        </span>
                        <h2 className="mt-5 text-3xl font-bold tracking-tight sm:text-4xl">
                            Des réclamations analysées, des décisions traçables
                        </h2>
                        <p className="mt-4 text-lg leading-relaxed text-gray-600">
                            Lorsqu’une réclamation est déposée, l’IA examine le contenu de la demande et l’état des charges de
                            l’appartement concerné. Elle produit une analyse structurée : décision, justification et traçabilité
                            complète.
                        </p>
                        <ul className="mt-6 space-y-3">
                            {[
                                'Analyse du contexte financier de l’appartement',
                                'Décision avec justification claire et consultable',
                                'Historique complet des audits par réclamation',
                                'Modèle d’IA paramétrable selon vos besoins',
                            ].map((item) => (
                                <li key={item} className="flex items-start gap-3 text-sm text-gray-700">
                                    <svg className="mt-0.5 h-5 w-5 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {item}
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
            </section>

            <section className="bg-gray-50/70 py-20">
                <div className="mx-auto max-w-4xl px-4 text-center sm:px-6">
                    <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">Prêt à simplifier la gestion de votre copropriété ?</h2>
                    <p className="mt-4 text-lg text-gray-600">Rejoignez SyndicFlow et centralisez toute la gestion en quelques minutes.</p>
                    <div className="mt-8 flex flex-wrap items-center justify-center gap-4">
                        <Link
                            to="/register"
                            className="rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700"
                        >
                            Créer un compte
                        </Link>
                        <Link
                            to="/login"
                            className="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:border-blue-300 hover:text-blue-600"
                        >
                            Se connecter
                        </Link>
                    </div>
                </div>
            </section>

            <footer id="contact" className="border-t border-gray-100 bg-white">
                <div className="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-3">
                    <div>
                        <Logo />
                        <p className="mt-4 max-w-xs text-sm leading-relaxed text-gray-500">
                            La plateforme de gestion de copropriété : résidences, charges, réclamations et analyse par intelligence
                            artificielle.
                        </p>
                    </div>
                    <div>
                        <p className="text-sm font-semibold text-gray-900">Navigation</p>
                        <ul className="mt-4 space-y-2.5">
                            {NAV_ITEMS.map((item) => (
                                <li key={item.href}>
                                    <a href={item.href} className="text-sm text-gray-500 transition hover:text-blue-600">
                                        {item.label}
                                    </a>
                                </li>
                            ))}
                        </ul>
                    </div>
                    <div>
                        <p className="text-sm font-semibold text-gray-900">Contact</p>
                        <ul className="mt-4 space-y-2.5 text-sm text-gray-500">
                            <li>contact@syndicflow.example</li>
                            <li>+33 (0)1 23 45 67 89</li>
                            <li>12 Avenue des Mimosas, 06000 Nice</li>
                        </ul>
                    </div>
                </div>
                <div className="border-t border-gray-100">
                    <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-3 px-4 py-6 text-xs text-gray-400 sm:flex-row sm:px-6">
                        <p>© {new Date().getFullYear()} SyndicFlow. Tous droits réservés.</p>
                        <p>Propulsé par Laravel · React · Intelligence artificielle</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
