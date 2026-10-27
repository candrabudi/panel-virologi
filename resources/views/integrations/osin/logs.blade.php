@extends('layouts.app')
@section('title', 'OSIN API Logs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/litepicker.css') }}">
    <style>
        .osin-logs {
            --osin-line: #e7e9ec;
            --osin-rule: #eff1f3;
            --osin-ink: #20242b;
            --osin-muted: #656c76;
            --osin-control: #cbd1d8;
        }

        .osin-logs .osin-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.25rem;
        }

        .osin-logs .osin-heading h1 {
            margin: 0;
            color: var(--osin-ink);
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1.25;
        }

        .osin-logs .osin-heading p {
            margin: .375rem 0 0;
            color: var(--osin-muted);
            font-size: .875rem;
            line-height: 1.45;
        }

        .osin-logs .osin-scope {
            margin-bottom: .4rem;
            color: var(--osin-muted);
            font-size: .8125rem;
        }

        .osin-logs .osin-visually-hidden {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            clip-path: inset(50%) !important;
            white-space: nowrap !important;
        }

        .osin-logs .osin-heading-actions {
            display: flex;
            flex: none;
            align-items: center;
            gap: .75rem;
        }

        .osin-logs .osin-connection {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: #166534;
            font-size: .8125rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .osin-logs .osin-connection-dot {
            width: .45rem;
            height: .45rem;
            flex: none;
            border-radius: 50%;
            background: #16a34a;
        }

        .osin-logs .osin-connection--partial {
            color: #92400e;
        }

        .osin-logs .osin-connection--partial .osin-connection-dot {
            background: #f59e0b;
        }

        .osin-logs .osin-refresh,
        .osin-logs .osin-page-link {
            display: inline-flex;
            min-height: 2.25rem;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            border: 1px solid var(--osin-control);
            border-radius: .375rem;
            padding: .4rem .7rem;
            color: #3f4752;
            background: #fff;
            font-size: .8125rem;
            font-weight: 500;
            line-height: 1.2;
            text-decoration: none;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .osin-logs .osin-refresh:hover,
        .osin-logs .osin-page-link:hover {
            border-color: #9aa3ad;
            background: #f8f9fa;
        }

        .osin-logs .osin-refresh:focus-visible,
        .osin-logs .osin-page-link:focus-visible,
        .osin-logs .osin-limit:focus-visible,
        .osin-logs .osin-filter:focus-visible,
        .osin-logs .osin-agent-details summary:focus-visible {
            outline: 2px solid #334155;
            outline-offset: 2px;
        }

        .osin-logs .osin-icon {
            display: block;
            width: 1rem;
            height: 1rem;
            flex: none;
            stroke-width: 1.75;
        }

        .osin-logs .osin-panel {
            overflow: hidden;
            border: 1px solid var(--osin-line);
            border-radius: .625rem;
            background: #fff;
        }

        .osin-logs .osin-analytics {
            overflow: hidden;
            border: 1px solid var(--osin-line);
            border-radius: .625rem;
            background: #fff;
        }

        .osin-logs .osin-analytics-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid var(--osin-rule);
            padding: 1rem 1.125rem;
        }

        .osin-logs .osin-analytics-heading h2,
        .osin-logs .osin-chart-heading h3 {
            margin: 0;
            color: var(--osin-ink);
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .osin-logs .osin-analytics-heading p,
        .osin-logs .osin-chart-heading p {
            margin: .2rem 0 0;
            color: var(--osin-muted);
            font-size: .8125rem;
            line-height: 1.4;
        }

        .osin-logs .osin-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            border-bottom: 1px solid var(--osin-rule);
        }

        .osin-logs .osin-stat {
            min-width: 0;
            padding: .9rem 1.125rem;
        }

        .osin-logs .osin-stat + .osin-stat {
            border-left: 1px solid var(--osin-rule);
        }

        .osin-logs .osin-stat-label {
            display: block;
            color: var(--osin-muted);
            font-size: .75rem;
            line-height: 1.4;
        }

        .osin-logs .osin-stat-value {
            display: block;
            margin-top: .25rem;
            overflow: hidden;
            color: var(--osin-ink);
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .osin-logs .osin-stat-note {
            display: block;
            margin-top: .15rem;
            color: var(--osin-muted);
            font-size: .7rem;
        }

        .osin-logs .osin-chart-card {
            padding: 1rem 1.125rem 1.125rem;
        }

        .osin-logs .osin-chart-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .osin-logs .osin-chart-unit {
            flex: none;
            color: var(--osin-muted);
            font-size: .75rem;
        }

        .osin-logs .osin-chart-scroll {
            overflow-x: auto;
            margin-top: .75rem;
            padding: .25rem .75rem .5rem;
        }

        .osin-logs .osin-chart {
            display: flex;
            min-width: max(100%, calc(var(--osin-point-count, 1) * 2.8rem));
            align-items: flex-end;
            gap: .5rem;
        }

        .osin-logs .osin-chart-column {
            min-width: 2.3rem;
            flex: 1 0 2.3rem;
            text-align: center;
        }

        .osin-logs .osin-chart-plot {
            position: relative;
            height: 8.5rem;
            border-bottom: 1px solid var(--osin-control);
            background: repeating-linear-gradient(to bottom, transparent 0, transparent 2rem, var(--osin-rule) 2.0625rem, transparent 2.125rem);
        }

        .osin-logs .osin-chart-bar {
            position: absolute;
            right: 22%;
            bottom: 0;
            left: 22%;
            height: var(--osin-bar-height, 0%);
            min-height: 2px;
            border-radius: .2rem .2rem 0 0;
            background: #256c61;
        }

        .osin-logs .osin-chart-value {
            position: absolute;
            z-index: 1;
            right: 0;
            bottom: calc(var(--osin-bar-height, 0%) + .2rem);
            left: 0;
            color: var(--osin-muted);
            font-size: .75rem;
            font-variant-numeric: tabular-nums;
        }

        .osin-logs .osin-chart-label {
            display: block;
            margin-top: .35rem;
            color: var(--osin-muted);
            font-size: .75rem;
            white-space: nowrap;
        }

        .osin-logs .osin-toolbar {
            display: flex;
            align-items: stretch;
            flex-direction: column;
            gap: .875rem;
            padding: 1rem 1.125rem;
        }

        .osin-logs .osin-toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem 1rem;
            flex-wrap: wrap;
        }

        .osin-logs .osin-toolbar h2 {
            margin: 0;
            color: var(--osin-ink);
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .osin-logs .osin-range {
            margin: .2rem 0 0;
            color: var(--osin-muted);
            font-size: .8125rem;
            line-height: 1.4;
        }

        .osin-logs .osin-controls {
            display: flex;
            min-width: 0;
            align-items: center;
            justify-content: flex-end;
            gap: .5rem;
        }

        .osin-logs .osin-date-filter {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .osin-logs .osin-date-field {
            display: flex;
            align-items: center;
            gap: .4rem;
            color: var(--osin-muted);
            font-size: .75rem;
            white-space: nowrap;
        }

        .osin-logs .osin-filter-wrap {
            position: relative;
            width: min(19rem, 35vw);
            min-width: 13rem;
        }

        .osin-logs .osin-search-icon {
            position: absolute;
            top: 50%;
            left: .75rem;
            width: 1rem;
            height: 1rem;
            transform: translateY(-50%);
            color: #727b86;
            pointer-events: none;
        }

        .osin-logs .osin-filter,
        .osin-logs .osin-limit,
        .osin-logs .osin-date-input {
            box-sizing: border-box;
            height: 2.375rem;
            margin: 0;
            border: 1px solid var(--osin-control);
            border-radius: .375rem;
            color: #303843;
            background-color: #fff;
            font: inherit;
            font-size: .8125rem;
            line-height: 1.25rem;
        }

        .osin-logs .osin-date-input {
            width: 10.5rem;
            padding: .4rem 2.1rem .4rem .65rem;
            cursor: pointer;
        }

        .osin-logs .osin-date-picker-wrap {
            position: relative;
            display: inline-flex;
            min-width: 0;
        }

        .osin-logs .osin-date-picker-icon {
            position: absolute;
            top: 50%;
            right: .65rem;
            width: 1rem;
            height: 1rem;
            transform: translateY(-50%);
            color: #727b86;
            pointer-events: none;
        }

        .osin-logs .osin-date-input:focus-visible {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgb(99 102 241 / 16%);
        }

        .dark .osin-logs .osin-date-picker-icon {
            color: #aab3bf;
        }

        .dark .osin-logs .osin-date-input:focus-visible {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgb(129 140 248 / 20%);
        }

        .osin-logs .osin-filter {
            display: block;
            width: 100%;
            padding: .5rem .75rem .5rem 2.35rem;
        }

        .osin-logs .osin-filter::placeholder {
            color: #69727d;
            opacity: 1;
        }

        .osin-logs .osin-limit {
            min-width: 6rem;
            padding: .4rem 1.9rem .4rem .65rem;
        }

        .osin-logs .osin-table-wrap {
            overflow-x: auto;
            border-top: 1px solid var(--osin-rule);
        }

        .osin-logs .osin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            table-layout: fixed;
        }

        .osin-logs .osin-table caption {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            clip-path: inset(50%);
            white-space: nowrap;
        }

        .osin-logs .osin-table th {
            padding: .65rem .875rem;
            color: #5e6670;
            font-size: .75rem;
            font-weight: 500;
            line-height: 1.35;
            white-space: nowrap;
        }

        .osin-logs .osin-table td {
            padding: .7rem .875rem;
            border-top: 1px solid var(--osin-rule);
            color: #313944;
            font-size: .8125rem;
            line-height: 1.4;
            vertical-align: top;
        }

        .osin-logs .osin-log-row:hover td {
            background: #fafbfc;
        }

        .dark .osin-logs .osin-log-row:hover td {
            background: rgb(255 255 255 / 3%);
        }

        .osin-logs .osin-activity-topline,
        .osin-logs .osin-activity-heading,
        .osin-logs .osin-result-primary {
            display: flex;
            align-items: center;
        }

        .osin-logs .osin-activity-topline {
            align-items: flex-start;
            justify-content: space-between;
            gap: .5rem;
            min-width: 0;
        }

        .osin-logs .osin-activity-heading {
            min-width: 0;
            flex-wrap: wrap;
            gap: .35rem .5rem;
        }

        .osin-logs .osin-activity-heading .osin-kind {
            margin: 0;
        }

        .osin-logs .osin-activity-heading .osin-id {
            margin: 0;
        }

        .osin-logs .osin-request-query {
            max-width: 100%;
            margin-top: .35rem;
            overflow-wrap: anywhere;
            color: var(--osin-ink);
            font-weight: 500;
        }

        .osin-logs .osin-detail-button {
            display: inline-flex;
            min-height: 1.75rem;
            flex: none;
            align-items: center;
            gap: .3rem;
            border: 1px solid #cbd1d8;
            border-radius: .375rem;
            padding: .2rem .45rem;
            color: #334155;
            background: #fff;
            font-size: .6875rem;
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
            cursor: pointer;
        }

        .osin-logs .osin-detail-button:hover {
            border-color: #9aa3ad;
            background: #f8fafc;
        }

        .osin-logs .osin-detail-button:focus-visible,
        .osin-logs .osin-detail-close:focus-visible,
        .osin-logs .osin-secret-toggle:focus-visible {
            outline: 2px solid #334155;
            outline-offset: 2px;
        }

        .osin-logs .osin-id,
        .osin-logs .osin-ip,
        .osin-logs .osin-time {
            font-variant-numeric: tabular-nums;
        }

        .osin-logs .osin-ip {
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-id {
            color: #5e6670;
            font-size: .75rem;
        }

        .osin-logs .osin-result-primary {
            min-width: 0;
            flex-wrap: wrap;
            gap: .35rem .5rem;
        }

        .osin-logs .osin-result-primary .osin-findings {
            margin: 0;
        }

        .osin-logs .osin-result-ip {
            display: block;
            margin-top: .3rem;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-mode {
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            border-radius: .25rem;
            padding: .16rem .42rem;
            color: #444b54;
            background: #f0f2f4;
            font-size: .75rem;
            line-height: 1.25;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .osin-logs .osin-kind,
        .osin-logs .osin-result-status {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: .16rem .5rem;
            font-size: .6875rem;
            font-weight: 600;
            line-height: 1.25;
            white-space: nowrap;
        }

        .osin-logs .osin-kind {
            margin: 0 .4rem .25rem 0;
            color: #334155;
            background: #f1f5f9;
        }

        .osin-logs .osin-kind--leakcheck {
            color: #6d28d9;
            background: #f3e8ff;
        }

        .osin-logs .osin-result-status--success,
        .osin-logs .osin-result-status--cached {
            color: #166534;
            background: #dcfce7;
        }

        .osin-logs .osin-result-status--failed,
        .osin-logs .osin-result-status--unknown {
            color: #9f1239;
            background: #ffe4e6;
        }

        .osin-logs .osin-findings {
            display: block;
            color: #6d28d9;
            font-size: .75rem;
            font-weight: 600;
        }

        .osin-logs .osin-client-meta,
        .osin-logs .osin-agent-meta,
        .osin-logs .osin-time-meta {
            margin-top: .25rem;
            color: #626b76;
            font-size: .75rem;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-agent-preview {
            max-width: 30rem;
            overflow-wrap: anywhere;
            color: #3f4752;
        }

        .osin-logs .osin-agent-details {
            margin-top: .3rem;
        }

        .osin-logs .osin-agent-details summary {
            display: inline-block;
            color: #4c5560;
            cursor: pointer;
            font-size: .75rem;
        }

        .osin-logs .osin-agent-full {
            max-width: 34rem;
            margin: .45rem 0 0;
            border-left: 2px solid #cbd1d8;
            padding-left: .625rem;
            color: #4c5560;
            font-size: .75rem;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-created {
            width: 1%;
            white-space: nowrap;
        }

        .osin-logs .osin-time {
            color: var(--osin-ink);
            font-size: .8125rem;
        }

        .osin-logs .osin-empty,
        .osin-logs .osin-filter-empty {
            padding: 2.5rem 1rem !important;
            color: #626b76 !important;
            text-align: center;
        }

        .osin-logs .osin-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-top: 1px solid var(--osin-rule);
            padding: .875rem 1.125rem;
        }

        .osin-logs .osin-count,
        .osin-logs .osin-page-indicator {
            color: #626b76;
            font-size: .8125rem;
            font-variant-numeric: tabular-nums;
        }

        .osin-logs .osin-pagination {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .osin-logs .osin-page-link[aria-disabled="true"] {
            border-color: #e2e5e9;
            color: #5e6670;
            background: #f8f9fa;
            cursor: not-allowed;
        }

        .osin-logs .osin-error {
            margin-top: 1.5rem;
            border: 1px solid #f0caca;
            border-radius: .5rem;
            padding: 1rem 1.125rem;
            color: #7f1d1d;
            background: #fffafa;
        }

        .osin-logs .osin-error-title {
            margin: 0;
            font-size: .9375rem;
            font-weight: 600;
        }

        .osin-logs .osin-error-copy {
            margin: .3rem 0 0;
            font-size: .8125rem;
        }

        .osin-logs .osin-error a {
            display: inline-block;
            margin-top: .6rem;
            color: #7f1d1d;
            font-size: .8125rem;
            font-weight: 600;
            text-decoration: underline;
        }

        .osin-logs .osin-detail-modal {
            position: fixed;
            z-index: 1000;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            padding: clamp(.75rem, 3vh, 2rem) 1rem;
            background: rgb(15 23 42 / 58%);
        }

        .osin-logs .osin-detail-modal[hidden] {
            display: none !important;
        }

        .osin-logs .osin-detail-dialog {
            display: flex;
            width: min(100%, 60rem);
            max-height: calc(100vh - 2rem);
            flex-direction: column;
            overflow: hidden;
            border: 1px solid var(--osin-line);
            border-radius: .5rem;
            background: #fff;
            box-shadow: 0 24px 64px rgb(15 23 42 / 25%);
        }

        .osin-logs .osin-detail-dialog-header,
        .osin-logs .osin-detail-dialog-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid var(--osin-rule);
            padding: .875rem 1rem;
        }

        .osin-logs .osin-detail-dialog-footer {
            justify-content: flex-end;
            border-top: 1px solid var(--osin-rule);
            border-bottom: 0;
        }

        .osin-logs .osin-detail-dialog-header h2 {
            margin: 0;
            color: var(--osin-ink);
            font-size: 1.125rem;
            font-weight: 600;
        }

        .osin-logs .osin-detail-subtitle {
            margin: .2rem 0 0;
            color: var(--osin-muted);
            font-size: .8125rem;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-detail-close {
            display: inline-flex;
            width: 2.25rem;
            height: 2.25rem;
            flex: none;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            border-radius: .375rem;
            color: var(--osin-muted);
            background: transparent;
            cursor: pointer;
        }

        .osin-logs .osin-detail-close:hover {
            color: var(--osin-ink);
            background: #f1f5f9;
        }

        .osin-logs .osin-detail-dialog-body {
            min-height: 0;
            max-height: min(68vh, 44rem);
            overflow: auto;
            padding: .875rem 1rem;
        }

        .osin-logs .osin-detail-state {
            padding: 2.5rem 1rem;
            color: var(--osin-muted);
            text-align: center;
        }

        .osin-logs .osin-detail-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: .875rem;
            border: 1px solid var(--osin-line);
            border-radius: .375rem;
            background: #f8fafc;
        }

        .osin-logs .osin-detail-chip {
            min-width: 0;
            padding: .6rem .7rem;
            color: #334155;
            font-size: .75rem;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-detail-chip + .osin-detail-chip {
            border-left: 1px solid var(--osin-line);
        }

        .osin-logs .osin-source-card {
            margin-top: .625rem;
            overflow: hidden;
            border: 1px solid var(--osin-line);
            border-radius: .375rem;
        }

        .osin-logs .osin-source-heading {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            justify-content: space-between;
            gap: .35rem .75rem;
            border-bottom: 1px solid var(--osin-rule);
            padding: .625rem .75rem;
            background: #f8fafc;
        }

        .osin-logs .osin-source-heading h3 {
            margin: 0;
            color: var(--osin-ink);
            font-size: .875rem;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-source-info {
            margin: .25rem 0 0;
            color: var(--osin-muted);
            font-size: .75rem;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-source-count {
            color: var(--osin-muted);
            font-size: .75rem;
            white-space: nowrap;
        }

        .osin-logs .osin-record-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 22rem), 1fr));
            align-items: start;
            gap: .5rem;
            padding: .75rem;
        }

        .osin-logs .osin-record {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 12rem), 1fr));
            align-content: start;
            gap: .5rem .75rem;
            min-width: 0;
            border: 1px solid var(--osin-line);
            border-radius: .375rem;
            padding: .65rem;
        }

        .osin-logs .osin-record-number {
            grid-column: 1 / -1;
            margin: 0;
            border-bottom: 1px solid var(--osin-rule);
            padding-bottom: .4rem;
            color: var(--osin-muted);
            font-size: .6875rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .osin-logs .osin-record-field {
            min-width: 0;
            border-bottom: 1px solid var(--osin-rule);
            padding: .25rem .25rem .45rem;
        }

        .osin-logs .osin-record-field + .osin-record-field {
            margin-top: 0;
        }

        .osin-logs .osin-record-nested {
            display: grid;
            gap: .35rem;
            margin: .35rem 0 0;
            border-left: 1px solid var(--osin-rule);
            padding-left: .5rem;
        }

        .osin-logs .osin-record-key {
            display: block;
            margin-bottom: .2rem;
            color: var(--osin-muted);
            font-size: .6875rem;
            font-weight: 600;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-record-value {
            display: block;
            color: var(--osin-ink);
            font-size: .8125rem;
            line-height: 1.4;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }

        .osin-logs .osin-secret-row {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
        }

        .osin-logs .osin-secret-row .osin-record-value {
            flex: 1;
        }

        .osin-logs .osin-secret-toggle {
            flex: none;
            border: 1px solid var(--osin-control);
            border-radius: .25rem;
            padding: .15rem .4rem;
            color: var(--osin-muted);
            background: transparent;
            font-size: .75rem;
            cursor: pointer;
        }

        .osin-logs .osin-detail-error {
            border: 1px solid #fecaca;
            border-radius: .375rem;
            padding: .75rem;
            color: #991b1b;
            background: #fef2f2;
            font-size: .8125rem;
            overflow-wrap: anywhere;
        }

        .osin-logs .osin-detail-note {
            margin-bottom: .75rem;
            border: 1px solid #cbd5e1;
            border-radius: .375rem;
            padding: .65rem .75rem;
            color: #475569;
            background: #f8fafc;
            font-size: .8125rem;
            overflow-wrap: anywhere;
        }

        .dark .osin-logs {
            --osin-line: #3f4854;
            --osin-rule: #35404c;
            --osin-ink: #e5e7eb;
            --osin-muted: #aab3bf;
            --osin-control: #586474;
        }

        .dark .osin-logs .osin-panel,
        .dark .osin-logs .osin-analytics,
        .dark .osin-logs .osin-filter,
        .dark .osin-logs .osin-limit,
        .dark .osin-logs .osin-date-input,
        .dark .osin-logs .osin-refresh,
        .dark .osin-logs .osin-page-link {
            color: #d9dee6;
            background-color: #252e39;
        }

        .dark .osin-logs .osin-filter::placeholder,
        .dark .osin-logs .osin-search-icon,
        .dark .osin-logs .osin-table th,
        .dark .osin-logs .osin-id,
        .dark .osin-logs .osin-client-meta,
        .dark .osin-logs .osin-agent-meta,
        .dark .osin-logs .osin-time-meta,
        .dark .osin-logs .osin-count,
        .dark .osin-logs .osin-page-indicator {
            color: #aab3bf;
        }

        .dark .osin-logs .osin-chart-bar {
            background: #5eb7a3;
        }

        .dark .osin-logs .osin-table td,
        .dark .osin-logs .osin-agent-preview,
        .dark .osin-logs .osin-agent-details summary,
        .dark .osin-logs .osin-agent-full {
            color: #d9dee6;
        }

        .dark .osin-logs .osin-mode {
            color: #d5dae2;
            background: #343e4b;
        }

        .dark .osin-logs .osin-kind {
            color: #d9dee6;
            background: #343e4b;
        }

        .dark .osin-logs .osin-kind--leakcheck {
            color: #e9d5ff;
            background: #4c1d95;
        }

        .dark .osin-logs .osin-result-status--success,
        .dark .osin-logs .osin-result-status--cached {
            color: #bbf7d0;
            background: rgb(22 101 52 / 30%);
        }

        .dark .osin-logs .osin-result-status--failed,
        .dark .osin-logs .osin-result-status--unknown {
            color: #fecdd3;
            background: rgb(159 18 57 / 30%);
        }

        .dark .osin-logs .osin-findings {
            color: #d8b4fe;
        }

        .dark .osin-logs .osin-error {
            border-color: #713c43;
            color: #fecaca;
            background: #331f24;
        }

        .dark .osin-logs .osin-detail-button,
        .dark .osin-logs .osin-detail-dialog {
            color: #d9dee6;
            background: #252e39;
        }

        .dark .osin-logs .osin-detail-button {
            border-color: #586474;
        }

        .dark .osin-logs .osin-detail-button:hover,
        .dark .osin-logs .osin-detail-close:hover {
            background: #343e4b;
        }

        .dark .osin-logs .osin-detail-chip {
            color: #d9dee6;
            background: #252e39;
        }

        .dark .osin-logs .osin-detail-summary {
            background: #252e39;
        }

        .dark .osin-logs .osin-source-heading {
            background: #202833;
        }

        .dark .osin-logs .osin-detail-error {
            border-color: #713c43;
            color: #fecaca;
            background: #331f24;
        }

        .dark .osin-logs .osin-detail-note {
            border-color: #475569;
            color: #cbd5e1;
            background: #202833;
        }

        @media (max-width: 767px) {
            .osin-logs .osin-heading {
                flex-direction: column;
                gap: .875rem;
            }

            .osin-logs .osin-heading-actions {
                width: 100%;
                justify-content: space-between;
            }

            .osin-logs .osin-toolbar {
                padding: .9rem;
            }

            .osin-logs .osin-toolbar-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .osin-logs .osin-date-filter {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .osin-logs .osin-date-field {
                min-width: 0;
                width: 100%;
                align-items: flex-start;
                flex-direction: column;
                gap: .25rem;
            }

            .osin-logs .osin-date-picker-wrap {
                width: 100%;
            }

            .osin-logs .osin-date-input {
                width: 100%;
            }

            .osin-logs .osin-date-submit,
            .osin-logs .osin-date-clear {
                width: 100%;
            }

            .osin-logs .osin-controls {
                width: 100%;
                justify-content: stretch;
            }

            .osin-logs .osin-filter-wrap {
                width: auto;
                min-width: 0;
                flex: 1 1 auto;
            }

            .osin-logs .osin-filter,
            .osin-logs .osin-limit,
            .osin-logs .osin-date-input {
                height: 2.75rem;
            }

            .osin-logs .osin-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .osin-logs .osin-stat:nth-child(3) {
                border-left: 0;
                border-top: 1px solid var(--osin-rule);
            }

            .osin-logs .osin-stat:nth-child(4) {
                border-top: 1px solid var(--osin-rule);
            }

            .osin-logs .osin-chart-card {
                padding: .9rem;
            }

            .osin-logs .osin-chart {
                min-width: max(100%, calc(var(--osin-point-count, 1) * 3.15rem));
                gap: .4rem;
            }

            .osin-logs .osin-chart-column {
                min-width: 2.75rem;
                flex-basis: 2.75rem;
            }

            .osin-logs .osin-table-wrap {
                overflow: visible;
            }

            .osin-logs .osin-table,
            .osin-logs .osin-table tbody {
                display: block;
                width: 100%;
            }

            .osin-logs .osin-table thead {
                position: absolute;
                width: 1px;
                height: 1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                clip-path: inset(50%);
            }

            .osin-logs .osin-table tbody tr.osin-log-row {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                gap: .45rem .75rem;
                border-top: 1px solid var(--osin-rule);
                padding: .875rem .9rem;
            }

            .osin-logs .osin-table tbody tr.osin-log-row td {
                min-width: 0;
                border: 0;
                padding: 0;
            }

            .osin-logs .osin-table tbody tr.osin-log-row td[data-column="request"] {
                grid-column: 1;
                grid-row: 1;
            }

            .osin-logs .osin-table tbody tr.osin-log-row td[data-column="created"] {
                grid-column: 2;
                grid-row: 1;
                text-align: right;
            }

            .osin-logs .osin-table tbody tr.osin-log-row td[data-column="client"] {
                display: flex;
                grid-column: 1 / -1;
                grid-row: 2;
                align-items: center;
                gap: .4rem .55rem;
                flex-wrap: wrap;
            }

            .osin-logs .osin-result-primary {
                width: 100%;
            }

            .osin-logs .osin-result-ip {
                margin-top: 0;
            }

            .osin-logs .osin-table tbody tr.osin-log-row td[data-column="agent"] {
                grid-column: 1 / -1;
                grid-row: 3;
            }

            .osin-logs .osin-client-meta {
                margin-top: 0;
            }

            .osin-logs .osin-agent-preview {
                max-width: none;
            }

            .osin-logs .osin-table tbody tr.osin-empty-row {
                display: block;
            }

            .osin-logs .osin-footer {
                align-items: flex-start;
                flex-direction: column;
                padding: .875rem .9rem;
            }

            .osin-logs .osin-pagination {
                width: 100%;
                justify-content: space-between;
            }

            .osin-logs .osin-detail-modal {
                padding: .5rem;
            }

            .osin-logs .osin-detail-dialog {
                max-height: calc(100vh - 1rem);
            }

            .osin-logs .osin-detail-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .osin-logs .osin-detail-chip:nth-child(odd) {
                border-left: 0;
            }

            .osin-logs .osin-detail-chip:nth-child(n + 3) {
                border-top: 1px solid var(--osin-line);
            }

            .osin-logs .osin-detail-dialog-header,
            .osin-logs .osin-detail-dialog-footer {
                padding: .75rem;
            }

            .osin-logs .osin-detail-dialog-body {
                max-height: 72vh;
                padding: .75rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $rowCount = count($logs);
        $rangeStart = $rowCount > 0
            ? (($pagination['page'] - 1) * $pagination['limit']) + 1
            : 0;
        $rangeEnd = $rowCount > 0
            ? min($pagination['total'], $rangeStart + $rowCount - 1)
            : 0;
        $retryUrl = route('integrations.osin.logs', [
            'page' => $pagination['page'],
            'limit' => $pagination['limit'],
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'refresh' => 1,
        ]);
        $clearDateUrl = route('integrations.osin.logs', ['limit' => $pagination['limit']]);
    @endphp

    <div class="col-span-12 osin-logs">
        <header class="osin-heading">
            <div class="min-w-0">
                <div class="osin-scope">OSIN · osin.virologi.info</div>
                <h1>API integration logs</h1>
                <p>
                    @if ($apiError)
                        OSIN activity is temporarily unavailable.
                    @else
                        {{ number_format($pagination['total']) }} activities in range
                        @if ($pagination['total'] > 0)
                            · page {{ number_format($pagination['page']) }} of {{ number_format($pagination['totalPages']) }}
                        @endif
                    @endif
                </p>
            </div>

            <div class="osin-heading-actions">
                @if (! $apiError)
                    <div class="text-right">
                        <span class="osin-connection {{ $apiWarnings ? 'osin-connection--partial' : '' }}">
                            <span class="osin-connection-dot" aria-hidden="true"></span>
                            {{ $apiWarnings ? 'Partial' : 'Connected' }}
                        </span>
                        @if ($fetchedAt)
                            <div class="osin-time-meta">Fetched {{ $fetchedAt->format('H:i:s') }} {{ $displayTimezone }}</div>
                        @endif
                    </div>
                @else
                    <span class="text-sm font-medium text-danger">Unavailable</span>
                @endif

                <a href="{{ $retryUrl }}" class="osin-refresh" aria-label="Refresh OSIN logs">
                    <i data-lucide="refresh-cw" class="osin-icon" aria-hidden="true"></i>
                    Refresh
                </a>
            </div>
        </header>

        @if ($apiError)
            <section class="osin-error" role="alert" aria-labelledby="osin-error-title">
                <h2 id="osin-error-title" class="osin-error-title">Could not load OSIN activity</h2>
                <p class="osin-error-copy">{{ $apiError }}</p>
                @foreach ($apiWarnings as $warning)
                    <p class="osin-error-copy">{{ $warning['source'] }}: {{ $warning['message'] }}</p>
                @endforeach
                <a href="{{ $retryUrl }}">Try again</a>
            </section>
        @else
            @if ($apiWarnings)
                <section class="osin-error mt-6" role="status" aria-label="Partial OSIN activity data">
                    <h2 class="osin-error-title">Some activity data could not be loaded</h2>
                    @foreach ($apiWarnings as $warning)
                        <p class="osin-error-copy">{{ $warning['source'] }}: {{ $warning['message'] }}</p>
                    @endforeach
                </section>
            @endif

            <section class="osin-analytics mt-6" aria-labelledby="osin-analytics-title">
                <div class="osin-analytics-heading">
                    <div>
                        <h2 id="osin-analytics-title">Activity analytics</h2>
                        <p>
                            @if ($dateFrom || $dateTo)
                                {{ $dateFrom ?: 'Earliest record' }} to {{ $dateTo ?: 'Latest record' }}
                            @else
                                Summary of API requests and leakcheck results
                            @endif
                        </p>
                    </div>
                    <span class="osin-chart-unit">{{ ucfirst($chart['unit_label']) }} trend</span>
                </div>

                <div class="osin-stat-grid">
                    <div class="osin-stat">
                        <span class="osin-stat-label">Activities in range</span>
                        <strong class="osin-stat-value">{{ number_format($analytics['total']) }}</strong>
                    </div>
                    <div class="osin-stat">
                        <span class="osin-stat-label">API requests</span>
                        <strong class="osin-stat-value">{{ number_format($analytics['api_requests']) }}</strong>
                    </div>
                    <div class="osin-stat">
                        <span class="osin-stat-label">Leakcheck scans</span>
                        <strong class="osin-stat-value">{{ number_format($analytics['leakcheck_scans']) }}</strong>
                    </div>
                    <div class="osin-stat">
                        <span class="osin-stat-label">Scans with findings</span>
                        <strong class="osin-stat-value">{{ number_format($analytics['scans_with_findings']) }}</strong>
                        <span class="osin-stat-note">{{ number_format($analytics['leak_count']) }} records reported</span>
                    </div>
                </div>

                <div class="osin-chart-card">
                    <div class="osin-chart-heading">
                        <div>
                            <h3>Activity volume</h3>
                            <p>API requests and leakcheck results grouped by {{ $chart['unit'] }}</p>
                        </div>
                    </div>

                    @if ($analytics['total'] > 0)
                        <div class="osin-chart-scroll">
                            <div class="osin-chart" style="--osin-point-count: {{ count($chart['points']) }}" role="img" aria-label="OSIN activity volume by {{ $chart['unit'] }}">
                                @foreach ($chart['points'] as $point)
                                    @php
                                        $barHeight = $point['count'] > 0
                                            ? max(5, (int) round(($point['count'] / $chart['max']) * 100))
                                            : 0;
                                    @endphp
                                    <div class="osin-chart-column" title="{{ $point['label'] }}: {{ number_format($point['count']) }} activities">
                                        <div class="osin-chart-plot" style="--osin-bar-height: {{ $barHeight }}%">
                                            <span class="osin-chart-value">{{ number_format($point['count']) }}</span>
                                            <span class="osin-chart-bar" aria-hidden="true"></span>
                                        </div>
                                        <span class="osin-chart-label">{{ $point['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="osin-range mt-4">No activity was recorded in this date range.</p>
                    @endif
                </div>
            </section>

            <section class="osin-panel mt-6" aria-labelledby="osin-activity-title">
                <div class="osin-toolbar">
                    <div class="min-w-0">
                        <h2 id="osin-activity-title">API and leakcheck activity</h2>
                        <p class="osin-range">
                            Showing {{ number_format($rangeStart) }}–{{ number_format($rangeEnd) }} of {{ number_format($pagination['total']) }} activities
                        </p>
                    </div>

                    <div class="osin-toolbar-actions">
                        <form method="GET" action="{{ route('integrations.osin.logs') }}" class="osin-date-filter">
                            <input type="hidden" name="page" value="1">
                            <input type="hidden" name="limit" value="{{ $pagination['limit'] }}">
                            <input id="osin-date-from-value" type="hidden" name="date_from" value="{{ $dateFrom }}">
                            <input id="osin-date-to-value" type="hidden" name="date_to" value="{{ $dateTo }}">
                            <label class="osin-date-field">
                                <span>From</span>
                                <span class="osin-date-picker-wrap">
                                    <input id="osin-date-from" type="text" class="osin-date-input" value="" placeholder="Select date" autocomplete="off" readonly aria-label="Start date">
                                    <i data-lucide="calendar-days" class="osin-date-picker-icon" aria-hidden="true"></i>
                                </span>
                            </label>
                            <label class="osin-date-field">
                                <span>To</span>
                                <span class="osin-date-picker-wrap">
                                    <input id="osin-date-to" type="text" class="osin-date-input" value="" placeholder="Select date" autocomplete="off" readonly aria-label="End date">
                                    <i data-lucide="calendar-days" class="osin-date-picker-icon" aria-hidden="true"></i>
                                </span>
                            </label>
                            <button type="submit" class="osin-refresh osin-date-submit">
                                <i data-lucide="calendar-range" class="osin-icon" aria-hidden="true"></i>
                                Apply
                            </button>
                            @if ($dateFrom || $dateTo)
                                <a href="{{ $clearDateUrl }}" class="osin-page-link osin-date-clear">Clear</a>
                            @endif
                        </form>

                        <div class="osin-controls">
                            <div class="osin-filter-wrap">
                                <i data-lucide="search" class="osin-icon osin-search-icon" aria-hidden="true"></i>
                                <input id="osin-log-filter" type="search" class="osin-filter"
                                    placeholder="Filter loaded activity"
                                    aria-label="Filter loaded OSIN activity" autocomplete="off">
                            </div>

                            <form method="GET" action="{{ route('integrations.osin.logs') }}">
                                <input type="hidden" name="page" value="1">
                                <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                                <input type="hidden" name="date_to" value="{{ $dateTo }}">
                                <label class="osin-visually-hidden" for="osin-log-limit">Rows per page</label>
                                <select id="osin-log-limit" name="limit" class="osin-limit" onchange="this.form.submit()">
                                    @foreach ([25, 50, 100] as $limitOption)
                                        <option value="{{ $limitOption }}" @selected($pagination['limit'] === $limitOption)>
                                            {{ $limitOption }} / page
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="osin-table-wrap">
                    <table class="osin-table">
                        <caption class="osin-visually-hidden">OSIN API and leakcheck activity entries</caption>
                        <colgroup>
                            <col style="width: 31%">
                            <col style="width: 22%">
                            <col style="width: 32%">
                            <col style="width: 15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">Activity and target</th>
                                <th scope="col">Result and client</th>
                                <th scope="col">Client details</th>
                                <th scope="col" class="text-right">Created at ({{ $displayTimezone }})</th>
                            </tr>
                        </thead>
                        <tbody id="osin-log-rows">
                            @forelse ($logs as $log)
                                <tr class="osin-log-row" data-search="{{ $log['search_text'] }}">
                                    <td data-column="request">
                                        <div class="osin-activity-topline">
                                            <div class="osin-activity-heading">
                                                <span class="osin-kind {{ $log['kind'] === 'leakcheck' ? 'osin-kind--leakcheck' : '' }}">{{ $log['kind_label'] }}</span>
                                                <span class="osin-id">#{{ $log['id'] !== '' ? $log['id'] : '-' }}</span>
                                            </div>
                                            @if ($log['kind'] === 'leakcheck' && ctype_digit($log['id']))
                                                <button type="button" class="osin-detail-button" data-leakcheck-detail
                                                    data-detail-url="{{ route('integrations.osin.leakcheck.detail', ['id' => $log['id']]) }}"
                                                    aria-label="View leakcheck data for result #{{ $log['id'] }}">
                                                    <i data-lucide="scan-eye" class="osin-icon" aria-hidden="true"></i>
                                                    View data
                                                </button>
                                            @endif
                                        </div>
                                        <div class="osin-request-query" title="{{ $log['query'] }}">
                                            {{ $log['query'] !== '' ? Str::limit($log['query'], 120) : 'No query recorded' }}
                                        </div>
                                    </td>
                                    <td data-column="client">
                                        @if ($log['kind'] === 'leakcheck')
                                            @php
                                                $statusClass = in_array($log['status'], ['success', 'cached'], true)
                                                    ? $log['status']
                                                    : ($log['status'] === 'failed' ? 'failed' : 'unknown');
                                            @endphp
                                            <div class="osin-result-primary">
                                                <span class="osin-result-status osin-result-status--{{ $statusClass }}">
                                                    {{ $log['status'] !== '' ? ucfirst($log['status']) : 'Unknown' }}
                                                </span>
                                                <span class="osin-findings">{{ number_format($log['leak_count']) }} found</span>
                                            </div>
                                            <span class="osin-ip osin-result-ip">{{ $log['ip_address'] !== '' ? $log['ip_address'] : '-' }}</span>
                                        @else
                                            <div class="osin-result-primary">
                                                <span class="osin-mode">{{ $log['mode'] !== '' ? $log['mode'] : '-' }}</span>
                                                <span class="osin-ip">{{ $log['ip_address'] !== '' ? $log['ip_address'] : '-' }}</span>
                                            </div>
                                            <span class="osin-client-meta">{{ $log['location'] !== '' ? $log['location'] : '-' }}</span>
                                        @endif
                                    </td>
                                    <td data-column="agent">
                                        @if ($log['kind'] === 'leakcheck' && $log['error_message'] !== '')
                                            <div class="osin-agent-preview" title="{{ $log['error_message'] }}">
                                                {{ Str::limit($log['error_message'], 96) }}
                                            </div>
                                            @if (mb_strlen($log['error_message']) > 96)
                                                <details class="osin-agent-details">
                                                    <summary>Show full error</summary>
                                                    <p class="osin-agent-full">{{ $log['error_message'] }}</p>
                                                </details>
                                            @endif
                                        @elseif ($log['kind'] === 'leakcheck')
                                            <span class="osin-agent-meta">No error reported</span>
                                        @elseif ($log['user_agent'] !== '')
                                            <div class="osin-agent-preview" title="{{ $log['user_agent'] }}">
                                                {{ Str::limit($log['user_agent'], 96) }}
                                            </div>
                                            @if (mb_strlen($log['user_agent']) > 96)
                                                <details class="osin-agent-details">
                                                    <summary>Show full user agent</summary>
                                                    <p class="osin-agent-full">{{ $log['user_agent'] }}</p>
                                                </details>
                                            @endif
                                        @else
                                            <span class="osin-agent-meta">-</span>
                                        @endif
                                    </td>
                                    <td data-column="created" class="osin-created">
                                        @if ($log['created_at_label'])
                                            <time class="osin-time" datetime="{{ $log['created_at'] }}">
                                                {{ $log['created_at_label'] }}
                                            </time>
                                        @else
                                            <span class="osin-time-meta">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="osin-empty-row">
                                    <td colspan="4" class="osin-empty">
                                        @if ($dateFrom || $dateTo)
                                            No activities match the selected date range.
                                        @else
                                            No API or leakcheck activity is recorded yet.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if ($rowCount > 0)
                                <tr id="osin-filter-empty" class="osin-empty-row" hidden>
                                    <td colspan="4" class="osin-filter-empty">
                                        No loaded activities match this filter.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <footer class="osin-footer">
                    <p id="osin-visible-count" class="osin-count" aria-live="polite">
                        Showing {{ number_format($rowCount) }} activities on this page
                    </p>

                    <nav class="osin-pagination" aria-label="OSIN log pages">
                        @if ($pagination['hasPreviousPage'])
                            <a class="osin-page-link" href="{{ route('integrations.osin.logs', ['page' => $pagination['page'] - 1, 'limit' => $pagination['limit'], 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}">
                                <i data-lucide="chevron-left" class="osin-icon" aria-hidden="true"></i>
                                Previous
                            </a>
                        @else
                            <span class="osin-page-link" aria-disabled="true">
                                <i data-lucide="chevron-left" class="osin-icon" aria-hidden="true"></i>
                                Previous
                            </span>
                        @endif

                        <span class="osin-page-indicator">
                            @if ($pagination['totalPages'] > 0)
                                Page {{ number_format($pagination['page']) }} of {{ number_format($pagination['totalPages']) }}
                            @else
                                No pages
                            @endif
                        </span>

                        @if ($pagination['hasNextPage'])
                            <a class="osin-page-link" href="{{ route('integrations.osin.logs', ['page' => $pagination['page'] + 1, 'limit' => $pagination['limit'], 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}">
                                Next
                                <i data-lucide="chevron-right" class="osin-icon" aria-hidden="true"></i>
                            </a>
                        @else
                            <span class="osin-page-link" aria-disabled="true">
                                Next
                                <i data-lucide="chevron-right" class="osin-icon" aria-hidden="true"></i>
                            </span>
                        @endif
                    </nav>
                </footer>
            </section>
        @endif

        <div id="osin-leakcheck-modal" class="osin-detail-modal" hidden aria-hidden="true">
            <section class="osin-detail-dialog" role="dialog" aria-modal="true" aria-labelledby="osin-detail-title">
                <header class="osin-detail-dialog-header">
                    <div class="min-w-0">
                        <h2 id="osin-detail-title">Leakcheck result</h2>
                        <p id="osin-detail-subtitle" class="osin-detail-subtitle"></p>
                    </div>
                    <button type="button" class="osin-detail-close" data-close-leakcheck aria-label="Close leakcheck details">
                        <i data-lucide="x" class="osin-icon" aria-hidden="true"></i>
                    </button>
                </header>
                <div id="osin-detail-content" class="osin-detail-dialog-body" aria-live="polite">
                    <p class="osin-detail-state">Select a leakcheck result above to view its data.</p>
                </div>
                <footer class="osin-detail-dialog-footer">
                    <button type="button" class="osin-page-link" data-close-leakcheck>Close</button>
                </footer>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dist/js/vendors/litepicker.js') }}"></script>
    <script>
        (() => {
            const dateFrom = document.getElementById('osin-date-from');
            const dateTo = document.getElementById('osin-date-to');
            const dateFromValue = document.getElementById('osin-date-from-value');
            const dateToValue = document.getElementById('osin-date-to-value');

            if (dateFrom && dateTo && dateFromValue && dateToValue && window.Litepicker) {
                new Litepicker({
                    element: dateFrom,
                    elementEnd: dateTo,
                    singleMode: false,
                    allowRepick: true,
                    autoApply: true,
                    format: 'MMM D, YYYY',
                    startDate: dateFromValue.value || undefined,
                    endDate: dateToValue.value || undefined,
                    numberOfMonths: window.matchMedia('(min-width: 768px)').matches ? 2 : 1,
                    numberOfColumns: window.matchMedia('(min-width: 768px)').matches ? 2 : 1,
                    showWeekNumbers: false,
                    setup: (picker) => {
                        picker.on('selected', (start, end) => {
                            dateFromValue.value = start ? start.format('YYYY-MM-DD') : '';
                            dateToValue.value = end ? end.format('YYYY-MM-DD') : '';
                        });
                    },
                });

                const formatDateForDisplay = (value) => {
                    if (!value) return '';
                    return new Date(value + 'T00:00:00').toLocaleDateString('en-US', {
                        month: 'short', day: 'numeric', year: 'numeric',
                    });
                };

                // Litepicker displays a range when both dates exist; keep one-sided filters visible too.
                if (!dateFrom.value) dateFrom.value = formatDateForDisplay(dateFromValue.value);
                if (!dateTo.value) dateTo.value = formatDateForDisplay(dateToValue.value);
            }

            const detailModal = document.getElementById('osin-leakcheck-modal');
            const detailSubtitle = document.getElementById('osin-detail-subtitle');
            const detailContent = document.getElementById('osin-detail-content');
            const detailClose = detailModal?.querySelector('[data-close-leakcheck]');
            if (detailModal) {
                // Portal the dialog to body so fixed positioning covers the full viewport,
                // including the app header and sidebar (some layout parents are transformed).
                const modalPortal = document.createElement('div');
                modalPortal.className = 'osin-logs';
                modalPortal.appendChild(detailModal);
                document.body.appendChild(modalPortal);
            }
            let detailRequest = null;
            let returnFocusTo = null;
            let previousBodyOverflow = '';

            const createElement = (tag, className, text) => {
                const element = document.createElement(tag);
                if (className) element.className = className;
                if (text !== undefined && text !== null) element.textContent = String(text);
                return element;
            };

            const valueToText = (value) => {
                if (value === null || value === undefined) return '-';
                if (typeof value === 'string') return value || '-';
                if (typeof value === 'object') {
                    try { return JSON.stringify(value, null, 2); } catch (error) { return '[Unable to display value]'; }
                }
                return String(value);
            };

            const isSensitiveField = (key) => /password|passwd|pass[_ -]?hash|token|secret|credential|authorization|cookie|api[_ -]?key/i.test(String(key));

            const appendField = (parent, key, value, depth = 0) => {
                const field = createElement('div', 'osin-record-field');
                field.appendChild(createElement('span', 'osin-record-key', key));

                if (isSensitiveField(key)) {
                    const row = createElement('div', 'osin-secret-row');
                    const valueNode = createElement('code', 'osin-record-value', 'Hidden until revealed');
                    const rawValue = valueToText(value);
                    const toggle = createElement('button', 'osin-secret-toggle', 'Reveal');
                    toggle.type = 'button';
                    toggle.setAttribute('aria-pressed', 'false');
                    toggle.addEventListener('click', () => {
                        const visible = toggle.getAttribute('aria-pressed') === 'true';
                        valueNode.textContent = visible ? 'Hidden until revealed' : rawValue;
                        toggle.textContent = visible ? 'Reveal' : 'Hide';
                        toggle.setAttribute('aria-pressed', visible ? 'false' : 'true');
                    });
                    row.append(valueNode, toggle);
                    field.appendChild(row);
                } else if (value !== null && typeof value === 'object' && depth < 6) {
                    const nested = createElement('div', 'osin-record-nested');
                    const entries = Array.isArray(value)
                        ? value.map((item, index) => [String(index + 1), item])
                        : Object.entries(value);
                    if (entries.length === 0) {
                        nested.appendChild(createElement('span', 'osin-record-value', Array.isArray(value) ? '[]' : '{}'));
                    } else {
                        entries.forEach(([childKey, childValue]) => appendField(nested, childKey, childValue, depth + 1));
                    }
                    field.appendChild(nested);
                } else {
                    field.appendChild(createElement('span', 'osin-record-value', valueToText(value)));
                }

                parent.appendChild(field);
            };

            const appendSummary = (detail) => {
                const summary = createElement('div', 'osin-detail-summary');
                let createdAt = detail.created_at || '-';
                if (createdAt !== '-') {
                    const parsedDate = new Date(createdAt);
                    if (!Number.isNaN(parsedDate.getTime())) {
                        createdAt = new Intl.DateTimeFormat(undefined, {
                            dateStyle: 'medium',
                            timeStyle: 'short',
                        }).format(parsedDate);
                    }
                }
                [
                    ['Status', detail.status || 'Unknown'],
                    ['Records reported', Number(detail.leak_count || 0).toLocaleString()],
                    ['IP', detail.ip_address || '-'],
                    ['Created at', createdAt],
                ].forEach(([label, value]) => summary.appendChild(createElement('span', 'osin-detail-chip', label + ': ' + value)));
                detailContent.appendChild(summary);
            };

            const appendSource = (name, source) => {
                const card = createElement('section', 'osin-source-card');
                const heading = createElement('div', 'osin-source-heading');
                const headingCopy = createElement('div', 'min-w-0');
                headingCopy.appendChild(createElement('h3', '', name || 'Unknown source'));

                const info = source && (source.InfoLeak ?? source.info_leak ?? source.info ?? '');
                if (info) headingCopy.appendChild(createElement('p', 'osin-source-info', valueToText(info)));
                heading.appendChild(headingCopy);

                const data = Array.isArray(source) ? source : (source && (source.Data ?? source.data ?? source.records ?? []));
                const records = Array.isArray(data) ? data : (data && typeof data === 'object' ? [data] : []);
                const reported = source && (source.NumOfResults ?? source.num_of_results ?? source.count);
                const recordCount = reported !== undefined && reported !== null ? Number(reported) : records.length;
                heading.appendChild(createElement('span', 'osin-source-count',
                    recordCount.toLocaleString() + (recordCount === 1 ? ' record' : ' records')));
                card.appendChild(heading);

                if (records.length === 0) {
                    card.appendChild(createElement('p', 'osin-detail-state', 'No record fields were included for this source.'));
                    detailContent.appendChild(card);
                    return;
                }

                const grid = createElement('div', 'osin-record-grid');
                card.appendChild(grid);
                let shown = 0;
                const chunkSize = 20;
                const loadChunk = () => {
                    records.slice(shown, shown + chunkSize).forEach((record) => {
                        const item = createElement('article', 'osin-record');
                        item.appendChild(createElement('div', 'osin-record-number', 'Record ' + (shown + 1)));
                        if (record && typeof record === 'object' && !Array.isArray(record)) {
                            Object.entries(record).forEach(([key, value]) => appendField(item, key, value));
                        } else {
                            appendField(item, 'Value', record);
                        }
                        grid.appendChild(item);
                        shown += 1;
                    });
                    if (loadMore) {
                        if (shown < records.length) {
                            loadMore.textContent = 'Load more (' + (records.length - shown).toLocaleString() + ' remaining)';
                        } else {
                            loadMore.remove();
                            loadMore = null;
                        }
                    }
                };
                let loadMore = null;
                if (records.length > chunkSize) {
                    loadMore = createElement('button', 'osin-detail-button');
                    loadMore.type = 'button';
                    loadMore.addEventListener('click', loadChunk);
                    card.appendChild(loadMore);
                }
                loadChunk();
                detailContent.appendChild(card);
            };

            const renderDetail = (detail) => {
                detailContent.replaceChildren();
                appendSummary(detail);

                const raw = detail.raw_response;
                let response = raw;
                if (typeof response === 'string') {
                    try { response = JSON.parse(response); } catch (error) { /* Keep non-JSON response as text below. */ }
                }

                const isCached = String(detail.status || '').toLowerCase() === 'cached'
                    || /served from cache/i.test(detail.error_message || '');
                if (isCached) {
                    const sourceCopy = detail.raw_response_source_id
                        ? 'This cached result reuses the records from original scan #' + detail.raw_response_source_id + '.'
                        : (detail.error_message || 'This scan was served from cache.');
                    detailContent.appendChild(createElement('div', 'osin-detail-note', sourceCopy));
                } else if (detail.error_message) {
                    detailContent.appendChild(createElement('div', 'osin-detail-error', detail.error_message));
                }

                if (response === null || response === undefined || response === '') {
                    const emptyCopy = detail.raw_response_source_id
                        ? 'The original scan was found, but OSIN did not return its detailed response.'
                        : 'OSIN did not include detailed records for this scan.';
                    detailContent.appendChild(createElement('p', 'osin-detail-state', emptyCopy));
                    return;
                }

                const sourceList = response && typeof response === 'object' ? (response.List ?? response.list) : null;
                if (sourceList && typeof sourceList === 'object') {
                    const sources = Array.isArray(sourceList)
                        ? sourceList.map((source, index) => [source?.Name || ('Source ' + (index + 1)), source])
                        : Object.entries(sourceList);
                    sources.forEach(([name, source]) => appendSource(name, source));

                    const metadata = Object.entries(response).filter(([key]) => !['List', 'list'].includes(key));
                    if (metadata.length) {
                        const metadataCard = createElement('section', 'osin-source-card');
                        const heading = createElement('div', 'osin-source-heading');
                        heading.appendChild(createElement('h3', '', 'Scan metadata'));
                        metadataCard.appendChild(heading);
                        const fields = createElement('div', 'osin-record');
                        metadata.forEach(([key, value]) => appendField(fields, key, value));
                        metadataCard.appendChild(fields);
                        detailContent.appendChild(metadataCard);
                    }
                    return;
                }

                if (typeof response === 'object') {
                    const card = createElement('section', 'osin-source-card');
                    const heading = createElement('div', 'osin-source-heading');
                    heading.appendChild(createElement('h3', '', 'Response details'));
                    card.appendChild(heading);
                    const fields = createElement('div', 'osin-record');
                    Object.entries(response).forEach(([key, value]) => appendField(fields, key, value));
                    card.appendChild(fields);
                    detailContent.appendChild(card);
                } else {
                    detailContent.appendChild(createElement('pre', 'osin-detail-error', String(response)));
                }
            };

            const closeDetailModal = () => {
                if (!detailModal) return;
                detailRequest?.abort();
                detailRequest = null;
                detailModal.hidden = true;
                detailModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = previousBodyOverflow;
                detailContent.replaceChildren();
                detailSubtitle.textContent = '';
                returnFocusTo?.focus();
            };

            if (detailModal && detailContent && detailSubtitle) {
                document.querySelectorAll('[data-leakcheck-detail]').forEach((button) => {
                    button.addEventListener('click', async () => {
                        detailRequest?.abort();
                        detailRequest = new AbortController();
                        returnFocusTo = button;
                        previousBodyOverflow = document.body.style.overflow;
                        detailModal.hidden = false;
                        detailModal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                        detailSubtitle.textContent = 'Loading result #' + (button.getAttribute('aria-label') || '').match(/#(\d+)/)?.[1];
                        detailContent.replaceChildren(createElement('p', 'osin-detail-state', 'Loading leakcheck data...'));
                        detailClose?.focus();

                        try {
                            const response = await fetch(button.dataset.detailUrl, {
                                headers: { Accept: 'application/json' },
                                credentials: 'same-origin',
                                cache: 'no-store',
                                signal: detailRequest.signal,
                            });
                            const payload = await response.json().catch(() => ({}));
                            if (!response.ok) throw new Error(payload.message || 'Leakcheck detail could not be loaded.');
                            detailSubtitle.textContent = (payload.query || 'No query recorded') + ' · Result #' + payload.id;
                            renderDetail(payload);
                        } catch (error) {
                            if (error.name === 'AbortError') return;
                            detailContent.replaceChildren(createElement('p', 'osin-detail-state', error.message || 'Leakcheck detail could not be loaded.'));
                        }
                    });
                });

                detailModal.querySelectorAll('[data-close-leakcheck]').forEach((button) => {
                    button.addEventListener('click', closeDetailModal);
                });
                detailModal.addEventListener('click', (event) => {
                    if (event.target === detailModal) closeDetailModal();
                });
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !detailModal.hidden) closeDetailModal();
                });
            }

            const filter = document.getElementById('osin-log-filter');
            const rows = Array.from(document.querySelectorAll('.osin-log-row'));
            const empty = document.getElementById('osin-filter-empty');
            const count = document.getElementById('osin-visible-count');

            if (!filter) return;

            filter.addEventListener('input', () => {
                const term = filter.value.trim().toLocaleLowerCase();
                let visible = 0;

                rows.forEach((row) => {
                    const matches = (row.dataset.search || row.textContent).toLocaleLowerCase().includes(term);
                    row.hidden = !matches;
                    if (matches) visible += 1;
                });

                if (empty) empty.hidden = visible > 0;
                if (count) {
                    count.textContent = term
                        ? 'Showing ' + visible + ' of ' + rows.length + ' activities on this page'
                        : 'Showing ' + rows.length + ' activities on this page';
                }
            });
        })();
    </script>
@endpush
