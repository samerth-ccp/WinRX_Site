function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

const {
  h
} = require('preact');

const classNames = require('classnames');

const ItemIcon = require('./components/ItemIcon');

const GridListItem = require('./components/GridLi');

const ListItem = require('./components/ListLi');

module.exports = props => {
  const {
    author
  } = props;
  const itemIconString = props.getItemIcon();
  const className = classNames('uppy-ProviderBrowserItem', {
    'uppy-ProviderBrowserItem--selected': props.isChecked
  }, {
    'uppy-ProviderBrowserItem--disabled': props.isDisabled
  }, {
    'uppy-ProviderBrowserItem--noPreview': itemIconString === 'video'
  });
  const itemIconEl = h(ItemIcon, {
    itemIconString: itemIconString
  });

  switch (props.viewType) {
    case 'grid':
      return h(GridListItem, _extends({}, props, {
        className: className,
        itemIconEl: itemIconEl
      }));

    case 'list':
      return h(ListItem, _extends({}, props, {
        className: className,
        itemIconEl: itemIconEl
      }));

    case 'unsplash':
      return h(GridListItem, _extends({}, props, {
        className: className,
        itemIconEl: itemIconEl
      }), h("a", {
        href: `${author.url}?utm_source=Companion&utm_medium=referral`,
        target: "_blank",
        rel: "noopener noreferrer",
        className: "uppy-ProviderBrowserItem-author"
      }, author.name));

    default:
      throw new Error(`There is no such type ${props.viewType}`);
  }
};