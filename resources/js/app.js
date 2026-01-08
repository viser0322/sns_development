/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

import React, { useState } from 'react';
import { Emoji, Picker } from 'emoji-mart';
import 'emoji-mart/css/emoji-mart.css';

const emojiTypeList = [
  'apple',
  'google',
  'twitter',
  'emojione',
  'messenger',
  'facebook',
];

function App() {
  const [emojiList, setEmojiList] = useState([]);
  const [emojiType, setEmojiType] = useState(null);

  const onClickButton = e => {
    setEmojiType(e.target.name);
  };

  const onSelect = emoji => {
    console.log({ emoji });
    setEmojiList([...emojiList, emoji]);
    setEmojiType(null);
  };
  return (
    <>
      <p>
        {emojiTypeList.map(name => (
          <button onClick={onClickButton} name={name} key={name}>
            {name}
          </button>
        ))}
      </p>
      {emojiType && (
        <Picker
          onSelect={emoji => onSelect({ ...emoji, emojiType })}
          set={emojiType}
          i18n={{
            search: '検索',
            categories: {
              search: '検索結果',
              recent: 'よく使う絵文字',
              people: '顔 & 人',
              nature: '動物 & 自然',
              foods: '食べ物 & 飲み物',
              activity: 'アクティビティ',
              places: '旅行 & 場所',
              objects: 'オブジェクト',
              symbols: '記号',
              flags: '旗',
              custom: 'カスタム',
            },
          }}
          style={{
            position: 'absolute',
            zIndex: '1',
          }}
        />
      )}
      {emojiList.length
        ? emojiList.map(({ id, emojiType }, i) => (
            <Emoji
              emoji={id}
              size={32}
              set={emojiType}
              onClick={emoji => onSelect({ ...emoji, emojiType })}
              key={i}
            />
          ))
        : null}
    </>
  );
}

export default App;

app.mount('#app');
