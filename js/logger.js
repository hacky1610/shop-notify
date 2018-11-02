class Logger {
  constructor() {
  }

  Info(text) {
    const d = new Date();
    console.log(`${d.toLocaleTimeString()} ${text}`);
  }
}

snLogger = new Logger();
