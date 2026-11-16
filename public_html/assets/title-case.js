/**
 * title-case.js — Gamtu Global Title Case Enforcement
 * Converts all visible text so every word starts with a capital letter.
 * Safe: skips <script>, <style>, inputs, prices, emails, URLs.
 * Works on static HTML + dynamic (API-loaded) content via MutationObserver.
 */
(function () {
  "use strict";

  /* ─── Tags whose TEXT content must never be transformed ─── */
  const SKIP_TAGS = new Set([
    "SCRIPT", "STYLE", "NOSCRIPT", "CODE", "PRE",
    "SVG", "MATH", "TEMPLATE", "IFRAME", "OBJECT",
    "INPUT", "TEXTAREA", "SELECT"
  ]);

  /* ─── Attributes used as placeholder/label text — handled separately ─── */
  const PLACEHOLDER_TAGS = new Set(["INPUT", "TEXTAREA"]);

  /* ─── Regex: patterns that must NOT be transformed ─── */
  const RE_PRICE   = /^[₹$€£¥][\d,]+(\.\d+)?$/;
  const RE_NUMBER  = /^[\d,]+(\.\d+)?%?$/;
  const RE_EMAIL   = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const RE_URL     = /^https?:\/\//i;
  const RE_SYMBOL  = /^[^\w]+$/;
  const RE_VERSION = /^v\d/i;

  /* ─── Convert a single word token to Title Case ─── */
  function titleWord(word) {
    if (!word || word.length === 0) return word;
    if (RE_PRICE.test(word))   return word;
    if (RE_NUMBER.test(word))  return word;
    if (RE_EMAIL.test(word))   return word;
    if (RE_URL.test(word))     return word;
    if (RE_SYMBOL.test(word))  return word;
    if (RE_VERSION.test(word)) return word;
    return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
  }

  /* ─── Convert a full string ─── */
  function toTitleCase(text) {
    if (!text || !text.trim()) return text;
    return text.replace(/(\S+)/g, function (token) {
      const match = token.match(/^([^a-zA-Z0-9₹$€£¥]*)([a-zA-Z0-9₹$€£¥].*?)([^a-zA-Z0-9]*)$/);
      if (!match) return token;
      const [, pre, core, post] = match;
      return pre + titleWord(core) + post;
    });
  }

  /* ─── Process a single text node ─── */
  function processTextNode(node) {
    const orig = node.nodeValue;
    if (!orig || !orig.trim()) return;
    const converted = toTitleCase(orig);
    if (converted !== orig) {
      node.nodeValue = converted;
    }
  }

  /* ─── Walk the DOM from a root element ─── */
  function walkNode(root) {
    if (!root) return;

    if (root.nodeType === Node.ELEMENT_NODE) {
      const tag = root.tagName;
      if (SKIP_TAGS.has(tag)) return;

      if (PLACEHOLDER_TAGS.has(tag) && root.placeholder) {
        const orig = root.placeholder;
        const conv = toTitleCase(orig);
        if (conv !== orig) root.placeholder = conv;
      }

      root.childNodes.forEach(walkNode);
      return;
    }

    if (root.nodeType === Node.TEXT_NODE) {
      processTextNode(root);
    }
  }

  /* ─── Initial pass ─── */
  function applyToAll() {
    walkNode(document.body);
  }

  /* ─── MutationObserver: dynamic content ─── */
  let rafPending = false;
  const mutBatch = new Set();

  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.type === "childList") {
        mutation.addedNodes.forEach(function (node) {
          mutBatch.add(node);
        });
      } else if (mutation.type === "characterData") {
        mutBatch.add(mutation.target);
      }
    });

    if (!rafPending) {
      rafPending = true;
      requestAnimationFrame(function () {
        mutBatch.forEach(function (node) {
          if (node.nodeType === Node.TEXT_NODE) {
            processTextNode(node);
          } else if (node.nodeType === Node.ELEMENT_NODE) {
            walkNode(node);
          }
        });
        mutBatch.clear();
        rafPending = false;
      });
    }
  });

  /* ─── Bootstrap ─── */
  function init() {
    applyToAll();
    observer.observe(document.body, {
      childList:     true,
      subtree:       true,
      characterData: true
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  /* ─── Expose globally ─── */
  window.toTitleCase = toTitleCase;
  window._ttc        = toTitleCase;

})();
